<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;
use Stripe\Event;
use Carbon\Carbon;
use Stripe\Webhook;
use App\Models\{
    User,
    Package,
    PaymentCard,
    Subscription
};
use App\Jobs\{
    SubscriptionCreatedMailJob,
    SubscriptionUpdatedMailJob,
    SubscriptionCancelledMailJob,
    SubscriptionPausedMailJob,
    SubscriptionResumedMailJob,
    CustomerCardUpdatedMailJob
};

class WebhookController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function handleStripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $event = null;
        Log::info('WebHook Start');
        Log::info('WebHook Start22');
        Log::info($sigHeader);
        Log::info($payload);
        try {
//            $event = Event::constructFrom(
//                json_decode($payload, true),
//                $sigHeader,
//                config('services.stripe.webhook_secret')
//            );
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                config('services.stripe.webhook_secret')
            );
            Log::info($event);
        } catch (\Exception $e) {
            return response()->json([
                "error" => 1,
                "message" => "Invalid webhook signature"
            ], 400);
        }
        Log::info($event->type);
        switch ($event->type) {

            case 'customer.subscription.created':
                $stripe_subscription = $event->data->object;
                $stripe_plan_id = $stripe_subscription->items->data[0]->price->id;

                try {
                    DB::beginTransaction();

                    $user = User::customer()->whereHas('customer_details', function ($query) use ($stripe_subscription) {
                        $query->where('pm_customer_id', $stripe_subscription->customer);
                    })->first();
                    $default_card = $user->payment_cards()->where('is_default', 1)->first();

                    $stripe_customer = $this->stripe->customers->retrieve($user->customer_details->pm_customer_id);
                    $stripe_card = $this->stripe->paymentMethods->retrieve($default_card->pm_id);

                    $package = Package::whereHas('payment_methods', function ($query) use ($stripe_plan_id) {
                        $query->where('pm_price_id', $stripe_plan_id);
                    })->first();
                    $package_pm = $package->payment_methods()->stripe()->first();

                    $amount = 0;
                    foreach ($stripe_subscription->items->data as $item) {
                        $amount += $item->plan->amount / 100;
                    }

                    $subscription = Subscription::create([
                        'user_id' => $user->id,
                        'package_id' => $package->id,
                        'pm_subscription_id' => $stripe_subscription->id,
                        'pm_customer_id' => $stripe_customer->id,
                        'pm_plan_id' => $package_pm->pm_price_id,
                        'pm_id' => $default_card->pm_id,
                        'payment_method' => 'stripe',
                        'name' => $package->title,
                        'amount' => $amount,
                        'start_at' => Carbon::createFromTimestamp($stripe_subscription->current_period_start)->toDateTimeString(),
                        'ended_at' => $stripe_subscription->ended_at ? Carbon::createFromTimestamp($stripe_subscription->ended_at)->toDateTimeString() : null,
                        'next_billing_date' => Carbon::createFromTimestamp($stripe_subscription->current_period_end)->toDateTimeString(),
                        'status' => $stripe_subscription->status,
                        'leads' => 0,
                        'payload' => json_encode($stripe_subscription)
                    ]);

                    $user->payment_cards()->where('is_default', 1)->update([
                        'is_default' => 0
                    ]);

                    $user_card = $user->payment_cards()->where('brand', $stripe_card->card->brand)->where('last4', $stripe_card->card->last4)->first();
                    if (is_null($user_card)) {
                        $user->payment_cards()->create([
                            'card_holder_name' => $stripe_card->billing_details->name ?? $stripe_customer->name,
                            'brand' => $stripe_card->card->brand,
                            'last4' => $stripe_card->card->last4,
                            'exp_month' => $stripe_card->card->exp_month,
                            'exp_year' => $stripe_card->card->exp_year,
                            'is_default' => 1,
                            'pm_id' => $stripe_card->id
                        ]);
                    } else {
                        $user_card->update([
                            'is_default' => 1
                        ]);
                    }

                    $user->update([
                        'pm_customer_id' => $stripe_customer->id
                    ]);

                    $stripe_invoices = $this->stripe->invoices->all([
                        'subscription' => $stripe_subscription->id,
                        'limit' => 1
                    ]);

                    if (count($stripe_invoices->data)) {
                        $stripe_invoice = $stripe_invoices->data[0];
                        $invoiceData = [
                            'user_id' => $user->id,
                            'package_id' => $subscription->package->id,
                            'subscription_id' => $subscription->id,
                            'pm_invoice_id' => $stripe_invoice->id,
                            'title' => $subscription->name,
                            'description' => $subscription->name . ' (' . Carbon::createFromTimestamp($stripe_subscription->current_period_start)->format('M d') . ' - ' . Carbon::createFromTimestamp($stripe_subscription->current_period_end)->format('M d Y') . ')',
                            'amount' => $stripe_invoice->amount_paid / 100,
                            'status' => $stripe_invoice->status,
                            'date' => Carbon::createFromTimestamp($stripe_invoice->created)->toDateTimeString()
                        ];

                        $subscription->invoices()->create($invoiceData);
                    }

                    if ($stripe_subscription->status == 'active' || $stripe_subscription->status == 'trialing') {
                        $user->license()->update([
                            'status' => 'active'
                        ]);
                    } else {
                        $user->license()->update([
                            'status' => 'deactive'
                        ]);
                    }

                    dispatch(new SubscriptionCreatedMailJob($user->id, $subscription->id, $package->id, null));

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Error: " . $e->getMessage());
                    return response()->json([
                        "error" => 1,
                        "message" => "Error: " . $e->getMessage()
                    ], 404);
                }
                break;
            case 'invoice.created':
                $invoice = $event->data->object;

                if ($invoice->subscription && $invoice->billing_reason === 'subscription_cycle') {
                    try {
                        DB::beginTransaction();
                        $stripe_subscription = $this->stripe->subscriptions->retrieve($invoice->subscription);

                        $user = User::customer()->whereHas('customer_details', function ($query) use ($invoice) {
                            $query->where('pm_customer_id', $invoice->customer);
                        })->first();

                        if (!$user || !$invoice->subtotal) {
                            break;
                        }
                        $gstAmount = intval($invoice->subtotal * 0.10);
                        $this->stripe->invoiceItems->create([
                            'customer' => $invoice->customer,
                            'amount' => $gstAmount,
                            'currency' => $invoice->currency,
                            'description' => '10% GST',
                            'invoice' => $invoice->id
                        ]);

                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error("GST Add Error: " . $e->getMessage());
                    }
                }
                break;

            case 'customer.subscription.trial_will_end':
                $stripe_subscription = $event->data->object;
                $stripe_plan_id = $stripe_subscription->items->data[0]->price->id;

                try {
                    DB::beginTransaction();

                    $user = User::customer()->whereHas('customer_details', function ($query) use ($stripe_subscription) {
                        $query->where('pm_customer_id', $stripe_subscription->customer);
                    })->first();
                    $default_card = $user->payment_cards()->where('is_default', 1)->first();
                    $subscription = $user->subscriptions()->where('pm_subscription_id', $stripe_subscription->id)->first();

                    $stripe_customer = $this->stripe->customers->retrieve($user->customer_details->pm_customer_id);
                    $stripe_card = $this->stripe->paymentMethods->retrieve($default_card->pm_id);

                    $package = Package::whereHas('payment_methods', function ($query) use ($stripe_plan_id) {
                        $query->where('pm_price_id', $stripe_plan_id);
                    })->first();
                    $package_pm = $package->payment_methods()->stripe()->first();

                    $subscription->update([
                        'trial_end_at' => Carbon::createFromTimestamp($stripe_subscription->trial_end)->toDateTimeString(),
                        'ended_at' => $stripe_subscription->ended_at ? Carbon::createFromTimestamp($stripe_subscription->ended_at)->toDateTimeString() : null,
                        'status' => $stripe_subscription->status
                    ]);

                    $stripe_invoices = $this->stripe->invoices->all([
                        'subscription' => $stripe_subscription->id,
                        'limit' => 1
                    ]);

                    if (count($stripe_invoices->data)) {
                        $stripe_invoice = $stripe_invoices->data[0];
                        $subscription->invoices()->create([
                            'user_id' => $user->id,
                            'package_id' => $subscription->package->id,
                            'subscription_id' => $subscription->id,
                            'pm_invoice_id' => $stripe_invoice->id,
                            'title' => $subscription->name,
                            'description' => $subscription->name . ' (' . Carbon::createFromTimestamp($stripe_subscription->current_period_start)->format('M d') . ' - ' . Carbon::createFromTimestamp($stripe_subscription->current_period_end)->format('M d Y') . ')',
                            'amount' => $stripe_invoice->amount_paid / 100,
                            'status' => $stripe_invoice->status,
                            'date' => Carbon::createFromTimestamp($stripe_invoice->created)->toDateTimeString()
                        ]);
                    }

                    if ($stripe_subscription->status == 'active' || $stripe_subscription->status == 'trialing') {
                        $user->license()->update([
                            'status' => 'active'
                        ]);
                    } else {
                        $user->license()->update([
                            'status' => 'deactive'
                        ]);
                    }

                    dispatch(new SubscriptionUpdatedMailJob($user->id, $subscription->id, $package->id, null));

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Error: " . $e->getMessage());
                    return response()->json([
                        "error" => 1,
                        "message" => "Error: " . $e->getMessage()
                    ], 404);
                }
                break;
            case 'customer.subscription.updated':
                $stripe_subscription = $event->data->object;
                $stripe_plan_id = $stripe_subscription->items->data[0]->price->id;
                Log::info('WebHook 1', $stripe_plan_id);
                Log::info('2');
                try {
                    DB::beginTransaction();

                    $user = User::customer()->whereHas('customer_details', function ($query) use ($stripe_subscription) {
                        $query->where('pm_customer_id', $stripe_subscription->customer);
                    })->first();
                    $subscription = $user->subscriptions()->where('pm_subscription_id', $stripe_subscription->id)->first();
                    $default_card = $user->payment_cards()->where('is_default', 1)->first();

                    $package = Package::whereHas('payment_methods', function ($query) use ($stripe_plan_id) {
                        $query->where('pm_price_id', $stripe_plan_id);
                    })->first();
                    $package_pm = $package->payment_methods()->stripe()->first();
                    Log::info('WebHook 2');
                    $amount = 0;
                    foreach ($stripe_subscription->items->data as $item) {
                        $amount += $item->plan->amount / 100;
                    }

                    $subscription->update([
                        'package_id' => $package->id,
                        'pm_subscription_id' => $stripe_subscription->id,
                        'pm_customer_id' => $stripe_subscription->customer,
                        'pm_plan_id' => $package_pm->pm_price_id,
                        'pm_id' => $default_card->pm_id,
                        'payment_method' => 'stripe',
                        'name' => $package->title,
                        'amount' => $amount,
                        'start_at' => Carbon::createFromTimestamp($stripe_subscription->current_period_start)->toDateTimeString(),
                        'ended_at' => $stripe_subscription->ended_at ? Carbon::createFromTimestamp($stripe_subscription->ended_at)->toDateTimeString() : null,
                        'next_billing_date' => Carbon::createFromTimestamp($stripe_subscription->current_period_end)->toDateTimeString(),
                        'status' => $stripe_subscription->status,
                        'leads' => 0,
                        'payload' => json_encode($stripe_subscription)
                    ]);
                    Log::info('WebHook 3');
                    $stripe_invoices = $this->stripe->invoices->all([
                        'subscription' => $stripe_subscription->id,
                        'limit' => 1
                    ]);

                    if (count($stripe_invoices->data)) {
                        $stripe_invoice = $stripe_invoices->data[0];
                        $subscription->invoices()->create([
                            'user_id' => $user->id,
                            'package_id' => $subscription->package->id,
                            'subscription_id' => $subscription->id,
                            'pm_invoice_id' => $stripe_invoice->id,
                            'title' => $subscription->name,
                            'description' => $subscription->name . ' (' . Carbon::createFromTimestamp($stripe_subscription->current_period_start)->format('M d') . ' - ' . Carbon::createFromTimestamp($stripe_subscription->current_period_end)->format('M d Y') . ')',
                            'amount' => $stripe_invoice->amount_paid / 100,
                            'status' => $stripe_invoice->status,
                            'date' => Carbon::createFromTimestamp($stripe_invoice->created)->toDateTimeString()
                        ]);
                    }
                    Log::info('WebHook 4');
                    if ($stripe_subscription->status == 'active' || $stripe_subscription->status == 'trialing') {
                        $user->license()->update([
                            'status' => 'active'
                        ]);
                    } else {
                        $user->license()->update([
                            'status' => 'deactive'
                        ]);
                    }
                    Log::info('WebHook 5');
                    dispatch(new SubscriptionUpdatedMailJob($user->id, $subscription->id, $package->id, null));
                    Log::info('WebHook 6');
                    DB::commit();
                } catch (\Exception $e) {
                    Log::info('WebHook 7');
                    DB::rollBack();
                    Log::error("Error: " . $e->getMessage());
                    return response()->json([
                        "error" => 1,
                        "message" => "Error: " . $e->getMessage()
                    ], 404);
                }
                break;
            case 'customer.subscription.deleted':
                $stripe_subscription = $event->data->object;

                try {
                    DB::beginTransaction();

                    $user = User::customer()->whereHas('customer_details', function ($query) use ($stripe_subscription) {
                        $query->where('pm_customer_id', $stripe_subscription->customer);
                    })->first();
                    $subscription = $user->subscriptions()->where('pm_subscription_id', $stripe_subscription->id)->first();

                    $subscription->update([
                        'status' => $stripe_subscription->status,
                        'ended_at' => $stripe_subscription->ended_at ? Carbon::createFromTimestamp($stripe_subscription->ended_at)->toDateTimeString() : null
                    ]);

                    $user->license()->update([
                        'status' => 'deactive'
                    ]);

                    dispatch(new SubscriptionCancelledMailJob($user->id, $subscription->id));

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Error: " . $e->getMessage());
                    return response()->json([
                        "error" => 1,
                        "message" => "Error: " . $e->getMessage()
                    ], 404);
                }
                break;
            case 'customer.subscription.paused':
                $stripe_subscription = $event->data->object;

                try {
                    DB::beginTransaction();

                    $user = User::customer()->whereHas('customer_details', function ($query) use ($stripe_subscription) {
                        $query->where('pm_customer_id', $stripe_subscription->customer);
                    })->first();
                    $subscription = $user->subscriptions()->where('pm_subscription_id', $stripe_subscription->id)->first();

                    $subscription->update([
                        'start_at' => Carbon::createFromTimestamp($stripe_subscription->current_period_start)->toDateTimeString(),
                        'next_billing_date' => Carbon::createFromTimestamp($stripe_subscription->current_period_end)->toDateTimeString(),
                        'ended_at' => $stripe_subscription->ended_at ? Carbon::createFromTimestamp($stripe_subscription->ended_at)->toDateTimeString() : null,
                        'paused_at' => now(),
                        'status' => $stripe_subscription->status,
                        'payload' => json_encode($stripe_subscription)
                    ]);

                    $user->license()->update([
                        'status' => 'deactive'
                    ]);

                    dispatch(new SubscriptionPausedMailJob($user->id, $subscription->id));

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Error: " . $e->getMessage());
                    return response()->json([
                        "error" => 1,
                        "message" => "Error: " . $e->getMessage()
                    ], 404);
                }
                break;
            case 'customer.subscription.resumed':
                $stripe_subscription = $event->data->object;
                $stripe_plan_id = $stripe_subscription->items->data[0]->price->id;

                try {
                    DB::beginTransaction();

                    $user = User::customer()->whereHas('customer_details', function ($query) use ($stripe_subscription) {
                        $query->where('pm_customer_id', $stripe_subscription->customer);
                    })->first();
                    $subscription = $user->subscriptions()->where('pm_subscription_id', $stripe_subscription->id)->first();

                    $subscription->update([
                        'start_at' => Carbon::createFromTimestamp($stripe_subscription->current_period_start)->toDateTimeString(),
                        'next_billing_date' => Carbon::createFromTimestamp($stripe_subscription->current_period_end)->toDateTimeString(),
                        'ended_at' => $stripe_subscription->ended_at ? Carbon::createFromTimestamp($stripe_subscription->ended_at)->toDateTimeString() : null,
                        'resumes_at' => Carbon::createFromTimestamp($stripe_subscription->pause_collection->resumes_at)->toDateTimeString(),
                        'status' => $stripe_subscription->status,
                        'payload' => json_encode($stripe_subscription)
                    ]);

                    $stripe_invoices = $this->stripe->invoices->all([
                        'subscription' => $stripe_subscription->id,
                        'limit' => 1
                    ]);

                    if (count($stripe_invoices->data)) {
                        $stripe_invoice = $stripe_invoices->data[0];
                        $subscription->invoices()->create([
                            'user_id' => $user->id,
                            'package_id' => $subscription->package->id,
                            'subscription_id' => $subscription->id,
                            'pm_invoice_id' => $stripe_invoice->id,
                            'title' => $subscription->name,
                            'description' => $subscription->name . ' (' . Carbon::createFromTimestamp($stripe_subscription->current_period_start)->format('M d') . ' - ' . Carbon::createFromTimestamp($stripe_subscription->current_period_end)->format('M d Y') . ')',
                            'amount' => $stripe_invoice->amount_paid / 100,
                            'status' => $stripe_invoice->status,
                            'date' => Carbon::createFromTimestamp($stripe_invoice->created)->toDateTimeString()
                        ]);
                    }

                    if ($stripe_subscription->status == 'active' || $stripe_subscription->status == 'trialing') {
                        $user->license()->update([
                            'status' => 'active'
                        ]);
                    } else {
                        $user->license()->update([
                            'status' => 'deactive'
                        ]);
                    }

                    dispatch(new SubscriptionResumedMailJob($user->id, $subscription->id));

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Error: " . $e->getMessage());
                    return response()->json([
                        "error" => 1,
                        "message" => "Error: " . $e->getMessage()
                    ], 404);
                }
                break;
            case 'invoice.paid':
                $stripe_invoice = $event->data->object;
                if ($stripe_invoice->subscription) {
                    try {
                        DB::beginTransaction();

                        $stripe_subscription = $this->stripe->subscriptions->retrieve($stripe_invoice->subscription);
                        $stripe_plan_id = $stripe_subscription->items->data[0]->price->id;

                        $user = User::customer()->whereHas('customer_details', function ($query) use ($stripe_subscription) {
                            $query->where('pm_customer_id', $stripe_subscription->customer);
                        })->first();
                        $default_card = $user->payment_cards()->where('is_default', 1)->first();
                        $subscription = $user->subscriptions()->where('pm_subscription_id', $stripe_subscription->id)->first();

                        $package = Package::whereHas('payment_methods', function ($query) use ($stripe_plan_id) {
                            $query->where('pm_price_id', $stripe_plan_id);
                        })->first();
                        $package_pm = $package->payment_methods()->stripe()->first();

                        $amount = 0;
                        foreach ($stripe_subscription->items->data as $item) {
                            $amount += $item->plan->amount / 100;
                        }

                        if (!is_null($subscription)) {
                            $subscription->update([
                                'package_id' => $package->id,
                                'pm_subscription_id' => $stripe_subscription->id,
                                'pm_customer_id' => $stripe_subscription->customer,
                                'pm_plan_id' => $package_pm->pm_price_id,
                                'pm_id' => $default_card->pm_id,
                                'payment_method' => 'stripe',
                                'name' => $package->title,
                                'amount' => $amount,
                                'start_at' => Carbon::createFromTimestamp($stripe_subscription->current_period_start)->toDateTimeString(),
                                'ended_at' => $stripe_subscription->ended_at ? Carbon::createFromTimestamp($stripe_subscription->ended_at)->toDateTimeString() : null,
                                'next_billing_date' => Carbon::createFromTimestamp($stripe_subscription->current_period_end)->toDateTimeString(),
                                'status' => $stripe_subscription->status,
                                'leads' => 0,
                                'payload' => json_encode($stripe_subscription)
                            ]);
                        } else {
                            $subscription = Subscription::create([
                                'user_id' => $user->id,
                                'package_id' => $package->id,
                                'pm_subscription_id' => $stripe_subscription->id,
                                'pm_customer_id' => $stripe_subscription->customer,
                                'pm_plan_id' => $package_pm->pm_price_id,
                                'pm_id' => $default_card->pm_id,
                                'payment_method' => 'stripe',
                                'name' => $package->title,
                                'amount' => $amount,
                                'start_at' => Carbon::createFromTimestamp($stripe_subscription->current_period_start)->toDateTimeString(),
                                'ended_at' => $stripe_subscription->ended_at ? Carbon::createFromTimestamp($stripe_subscription->ended_at)->toDateTimeString() : null,
                                'next_billing_date' => Carbon::createFromTimestamp($stripe_subscription->current_period_end)->toDateTimeString(),
                                'status' => $stripe_subscription->status,
                                'leads' => 0,
                                'payload' => json_encode($stripe_subscription)
                            ]);
                        }

                        $stripe_invoices = $this->stripe->invoices->all([
                            'subscription' => $stripe_subscription->id,
                            'limit' => 1
                        ]);

                        if (count($stripe_invoices->data)) {
                            $stripe_invoice = $stripe_invoices->data[0];
                            $subscription->invoices()->create([
                                'user_id' => $user->id,
                                'package_id' => $subscription->package->id,
                                'subscription_id' => $subscription->id,
                                'pm_invoice_id' => $stripe_invoice->id,
                                'title' => $subscription->name,
                                'description' => $subscription->name . ' (' . Carbon::createFromTimestamp($stripe_subscription->current_period_start)->format('M d') . ' - ' . Carbon::createFromTimestamp($stripe_subscription->current_period_end)->format('M d Y') . ')',
                                'amount' => $stripe_invoice->amount_paid / 100,
                                'status' => $stripe_invoice->status,
                                'date' => Carbon::createFromTimestamp($stripe_invoice->created)->toDateTimeString()
                            ]);
                        }

                        if ($stripe_subscription->status == 'active' || $stripe_subscription->status == 'trialing') {
                            $user->license()->update([
                                'status' => 'active'
                            ]);
                        } else {
                            $user->license()->update([
                                'status' => 'deactive'
                            ]);
                        }

                        dispatch(new SubscriptionUpdatedMailJob($user->id, $subscription->id, $package->id, null));

                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error("Error: " . $e->getMessage());
                        return response()->json([
                            "error" => 1,
                            "message" => "Error: " . $e->getMessage()
                        ], 404);
                    }
                }
                break;
            case 'invoice.payment_failed':
                $stripe_invoice = $event->data->object;
                if ($stripe_invoice->subscription) {
                    try {
                        DB::beginTransaction();

                        $stripe_subscription = $this->stripe->subscriptions->retrieve($stripe_invoice->subscription);
                        $user = User::customer()->whereHas('customer_details', function ($query) use ($stripe_subscription) {
                            $query->where('pm_customer_id', $stripe_subscription->customer);
                        })->first();
                        $subscription = $user->subscriptions()->where('pm_subscription_id', $stripe_subscription->id)->first();

                        $subscription->update([
                            'status' => 'canceled',
                            'ended_at' => now()
                        ]);

                        $this->stripe->subscriptions->cancel($stripe_invoice->subscription);

                        $user->license()->update([
                            'status' => 'deactive'
                        ]);

                        dispatch(new SubscriptionCancelledMailJob($user->id, $subscription->id));

                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error("Error: " . $e->getMessage());
                        return response()->json([
                            "error" => 1,
                            "message" => "Error: " . $e->getMessage()
                        ], 404);
                    }
                }
                break;
            case 'payment_method.automatically_updated':
                $stripe_payment_method = $event->data->object;
                if ($stripe_payment_method) {
                    try {
                        DB::beginTransaction();

                        $user = User::customer()->whereHas('customer_details', function ($query) use ($stripe_payment_method) {
                            $query->where('pm_customer_id', $stripe_payment_method->customer);
                        })->first();
                        $user_card = $user->payment_cards()->where('brand', $stripe_payment_method->card->brand)->where('last4', $stripe_payment_method->card->last4)->first();
                        $stripe_customer = $this->stripe->customers->retrieve($user->customer_details->pm_customer_id);
                        $subscription = $user->subscriptions()->where('pm_customer_id', $user->customer_details->pm_customer_id)->orderby('id', 'desc')->first();
                        $user->payment_cards()->where('is_default', 1)->update([
                            'is_default' => 0
                        ]);

                        if (is_null($user_card)) {
                            $user->payment_cards()->create([
                                'card_holder_name' => $stripe_payment_method->billing_details->name ?? $stripe_customer->name,
                                'brand' => $stripe_payment_method->card->brand,
                                'last4' => $stripe_payment_method->card->last4,
                                'exp_month' => $stripe_payment_method->card->exp_month,
                                'exp_year' => $stripe_payment_method->card->exp_year,
                                'is_default' => 1,
                                'pm_id' => $stripe_payment_method->id
                            ]);
                        } else {
                            $user_card->update([
                                'card_holder_name' => $stripe_payment_method->billing_details->name ?? $stripe_customer->name,
                                'brand' => $stripe_payment_method->card->brand,
                                'last4' => $stripe_payment_method->card->last4,
                                'exp_month' => $stripe_payment_method->card->exp_month,
                                'exp_year' => $stripe_payment_method->card->exp_year,
                                'is_default' => 1,
                                'pm_id' => $stripe_payment_method->id
                            ]);
                        }

                        if (!is_null($subscription)) {
                            $subscription->update([
                                'pm_id' => $stripe_payment_method->id
                            ]);
                        }

                        dispatch(new CustomerCardUpdatedMailJob($user->id));

                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error("Error: " . $e->getMessage());
                        return response()->json([
                            "error" => 1,
                            "message" => "Error: " . $e->getMessage()
                        ], 404);
                    }
                }
                break;
            default:
                // Ignore unrecognized event types
                break;
        }

        return response()->json(['success' => true]);
    }
}
