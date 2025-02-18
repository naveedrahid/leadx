<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;
use App\Traits\ApiPaginate;
use App\Http\Requests\{
    SubscriptionPaymentRequest,
    SubscriptionUpgradeRequest,
    ApplyDiscountRequest,
    AddCardRequest,
    SubscriptionWebsitesUpdateRequest
};
use App\Models\{
    User,
    Package,
    PaymentCard,
    Subscription,
    SubscriptionInvoices,
    Coupon,
    UserPaymentLink
};
use Carbon\Carbon;
use App\Jobs\{
    SubscriptionCreatedMailJob,
    SubscriptionCreatedByAdminMailJob,
    SubscriptionUpdatedMailJob,
    SubscriptionUpdatedByAdminMailJob,
    SubscriptionCancelledMailJob,
    SubscriptionCancelledByAdminMailJob,
    SubscriptionPausedMailJob,
    SubscriptionPausedByAdminMailJob,
    SubscriptionResumedMailJob,
    SubscriptionResumedByAdminMailJob,
    CustomerAutoRenewalDisabledMailJob,
    CustomerAutoRenewalDisabledByAdminMailJob,
    CustomerAutoRenewalEnabledMailJob,
    CustomerAutoRenewalEnabledByAdminMailJob,
    CustomerCardAddedMailJob,
    CustomerCardAddedByAdminMailJob,
    CustomerCardRemovedMailJob,
    CustomerCardRemovedByAdminMailJob,
    CustomerCardUpdatedMailJob,
    CustomerCardUpdatedByAdminMailJob,
    CustomerPaymentLinkGenerateByAdminMailJob
};
use Illuminate\Support\Str;
use Stripe\PaymentLink;

class SubscriptionController extends Controller
{
    use ApiPaginate;

//    protected $stripe;
//
//    public function __construct() {
//        $this->stripe = new StripeClient(config('services.stripe.secret'));
//    }

    public function resolveUser(Request $request) {
        if($request->filled('user_id')) {
            return User::whereId($request->user_id)->first();
        } else {
            return $request->user();
        }
    }

    public function get_count(Request $request)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $count = Subscription::filterSubscriptions($request)->count();
        return response()->json([
            "error" => 0,
            "data" => [
                "count" => $count
            ],
            "message" => "Customers count have been successfully retrieved"
        ], 200);
    }

    public function get_all(Request $request)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $order = (object) [
            'orderby' => $request->filled('orderby') ? $request->orderby : 'id',
            'order' => $request->filled('order') ? $request->order : 'DESC',
        ];

        $subscriptionQuery = Subscription::where('user_id', $user->id)->filterSubscriptions($request)->orderBy($order->orderby, $order->order);

        if ($request->filled('perpage')) {
            $subscriptions = $subscriptionQuery->paginate($request->perpage);
        } else {
            if($request->filled('limit')) {
                $subscriptionQuery->limit($request->limit);
            }
            $subscriptions = $subscriptionQuery->get();
        }

        $subscriptions->load('user', 'package', 'websites', 'coupon');

        $response = [
            'error' => 0,
            'data' => $request->filled('perpage') ? $subscriptions->items() : $subscriptions,
            'message' => 'Subscriptions have been successfully retrieved.'
        ];

        if($request->filled('perpage')) {
            $response['paginate'] = $this->paginate($subscriptions);
        }

        return response()->json($response, 200);
    }

    public function send_payment_link(Request $request, $package_id) {
        $customer = $this->resolveUser($request);
        if(is_null($customer)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $package = Package::whereId($package_id)->where('status', 'active')->first();
        if (is_null($package)) {
            return response()->json([
                "error" => 1,
                "message" => "Package Not Found!"
            ], 404);
        }

        $paymentlink = UserPaymentLink::create([
            'user_id' => $customer->id,
            'package_id' => $package->id,
            'uuid' => Str::uuid(),
            'status' => 'pending'
        ]);

        if (is_null($paymentlink)) {
            return response()->json([
                "error" => 1,
                "message" => "Payment Link Not Found!"
            ], 404);
        }

        dispatch(new CustomerPaymentLinkGenerateByAdminMailJob($customer, $package, $paymentlink));

        return response()->json([
            "error" => 0,
            "data" => [],
            "message" => "Payment link has been sent successfully."
        ], 200);
    }

    public function get_payment_link(Request $request, $uuid) {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $user_payment_link = UserPaymentLink::with('package')->where('user_id', $user->id)->where('uuid', $uuid)->first();
        if (is_null($user_payment_link)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $date = Carbon::now()->subDays(10);
        if ($user_payment_link->created_at < $date) {
            return response()->json([
                "error" => 1,
                "message" => "Payment Link Expired!"
            ], 404);
        }

        return response()->json([
            "error" => 0,
            "data" => $user_payment_link,
            "message" => "Payment details have been retrieved successfully."
        ], 200);
    }

    public function get_cards(Request $request) {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $order = (object) [
            'orderby' => $request->filled('orderby') ? $request->orderby : 'id',
            'order' => $request->filled('order') ? $request->order : 'DESC',
        ];

        $paymentCardQuery = PaymentCard::filterPaymentCards($request)->orderBy($order->orderby, $order->order);

        if ($request->filled('perpage')) {
            $paymentCards = $paymentCardQuery->paginate($request->perpage);
        } else {
            if($request->filled('limit')) {
                $paymentCardQuery->limit($request->limit);
            }
            $paymentCards = $paymentCardQuery->get();
        }

        $response = [
            'error' => 0,
            'data' => $request->filled('perpage') ? $paymentCards->items() : $paymentCards,
            'message' => 'Cards have been successfully retrieved.'
        ];

        if($request->filled('perpage')) {
            $response['paginate'] = $this->paginate($paymentCards);
        }

        return response()->json($response, 200);
    }

    public function add_card(AddCardRequest $request) {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $user_card = null;
        if($request->payment_method == 'stripe') {
            try {
                if($user->customer_details->pm_customer_id === null) {
                    $stripe_customer_data = [
                        'name' => $user->fullname,
                        'email' => $user->email,
                        'phone' => $user->phone_number,
                        'payment_method' => $request->paymentMethodId
                    ];

                    if($request->set_to_default === true) {
                        $stripe_customer_data['invoice_settings'] = [
                            'default_payment_method' => $request->paymentMethodId
                        ];
                    }

                    $stripe_customer = $this->stripe->customers->create($stripe_customer_data);

                    $user->customer_details()->update([
                        'pm_customer_id' => $stripe_customer->id
                    ]);
                } else {
                    $stripe_customer = $this->stripe->customers->retrieve($user->customer_details->pm_customer_id);
                    if($stripe_customer->invoice_settings->default_payment_method != $request->paymentMethodId) {
                        $this->stripe->paymentMethods->attach($request->paymentMethodId, [
                            'customer' => $user->customer_details->pm_customer_id
                        ]);

                        if($request->set_to_default === true) {
                            $stripe_customer = $this->stripe->customers->update($stripe_customer->id, [
                                'invoice_settings' => [
                                    'default_payment_method' => $request->paymentMethodId
                                ]
                            ]);
                        }
                    }
                }
            } catch (\Exception $exception) {
                throw new HttpResponseException(response()->json([
                    "error" => 1,
                    "message" => "Error: " . $exception->getMessage()
                ], 404));
            }

            $user->customer_details()->update([
                'pm_customer_id' => $stripe_customer->id
            ]);

            $stripe_card = $this->getStripeCard($request->paymentMethodId);
            if($request->set_to_default === true) {
                $user->payment_cards()->where('is_default', 1)->update([
                    'is_default' => 0
                ]);
            }

            $user_card = $user->payment_cards()->where('brand', $stripe_card->card->brand)->where('last4', $stripe_card->card->last4)->first();
            if(!is_null($user_card)) {
                if($request->set_to_default === true) {
                    $user_card->update([
                        'card_holder_name' => $request->card_holder_name,
                        'brand' => $stripe_card->card->brand,
                        'last4' => $stripe_card->card->last4,
                        'exp_month' => $stripe_card->card->exp_month,
                        'exp_year' => $stripe_card->card->exp_year,
                        'is_default' => 1,
                        'pm_id' => $request->paymentMethodId
                    ]);
                }

                return response()->json([
                    "error" => 1,
                    "message" => "Card Already Exists"
                ], 404);
            }

            $user_card_data = [
                'card_holder_name' => $request->card_holder_name,
                'brand' => $stripe_card->card->brand,
                'last4' => $stripe_card->card->last4,
                'exp_month' => $stripe_card->card->exp_month,
                'exp_year' => $stripe_card->card->exp_year,
                'is_default' => 0,
                'pm_id' => $request->paymentMethodId
            ];

            if($request->set_to_default === true) {
                $user_card_data['is_default'] = 1;
            }

            $new_card = $user->payment_cards()->create($user_card_data);

            if($request->filled('user_id')) {
                dispatch(new CustomerCardAddedByAdminMailJob($user->id, $new_card->id));
            } else {
                dispatch(new CustomerCardAddedMailJob($user->id, $new_card->id));
            }
        }

        return response()->json([
            "error" => 0,
            "data" => $user_card,
            "message" => "Card have been added successfully."
        ], 200);
    }

    public function remove_card(Request $request, $id) {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $payment_card = PaymentCard::whereId($id)->where('user_id', $user->id)->first();
        if(is_null($payment_card)) {
            return response()->json([
                'error' => 1,
                'message' => 'Card not found!',
            ], 404);
        }

        if($request->payment_method == 'stripe') {
            try {
                DB::beginTransaction();

                $paymentMethod = $this->stripe->paymentMethods->retrieve($payment_card->pm_id);
                $this->stripe->paymentMethods->detach($paymentMethod->id);
                $payment_card->delete();

                if($request->filled('user_id')) {
                    dispatch(new CustomerCardRemovedByAdminMailJob($user->id));
                } else {
                    dispatch(new CustomerCardRemovedMailJob($user->id));
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    "error" => 1,
                    "message" => "Error: ". $e->getMessage()
                ], 400);
            }
        }

        return response()->json([
            "error" => 0,
            "data" => $payment_card,
            "message" => "Card have been removed successfully."
        ], 200);
    }

    public function default_card(Request $request) {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $default_card = PaymentCard::where(function($query){
            $currentYear = now()->year;
            $currentMonth = now()->month;
            $query->where('exp_year', '>', $currentYear);
            $query->orWhere(function ($subQuery) use ($currentYear, $currentMonth) {
                $subQuery->where('exp_year', '=', $currentYear)
                         ->where('exp_month', '>=', $currentMonth);
            });
        })->where('user_id', $user->id)->where('is_default', 1)->first();
        if(is_null($default_card)) {
            return response()->json([
                'error' => 1,
                'message' => 'Default Card not found!',
            ], 404);
        }

        return response()->json([
            "error" => 0,
            "data" => $default_card,
            "message" => "Default Card have been successfully retrieved."
        ], 200);
    }

    public function change_payment_method(Request $request) {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $user_card = null;
        if($request->payment_method == 'stripe') {
            if($user->customer_details->pm_customer_id !== null) {
                try {
                    $stripe_customer = $this->stripe->customers->retrieve($user->customer_details->pm_customer_id);
                    if($stripe_customer->invoice_settings->default_payment_method != $request->paymentMethodId) {
                        $this->stripe->paymentMethods->attach($request->paymentMethodId, [
                            'customer' => $user->customer_details->pm_customer_id
                        ]);

                        $stripe_customer = $this->stripe->customers->update($stripe_customer->id, [
                            'invoice_settings' => [
                                'default_payment_method' => $request->paymentMethodId
                            ]
                        ]);
                    }
                } catch (\Exception $exception) {
                    throw new HttpResponseException(response()->json([
                        "error" => 1,
                        "message" => "Error: " . $exception->getMessage()
                    ], 404));
                }
            }
        }

        $user->payment_cards()->where('is_default', 1)->update([
            'is_default' => 0
        ]);

        $user_card = $user->payment_cards()->where('pm_id', $request->paymentMethodId)->first();
        if(!is_null($user_card)) {
            $user_card->update([
                'is_default' => 1
            ]);
        }

        $subscription = $user->subscriptions()->where('pm_customer_id', $user->customer_details->pm_customer_id)->orderby('id', 'desc')->first();
        if(!is_null($subscription)) {
            $subscription->update([
                'pm_id' => $request->paymentMethodId
            ]);
        }

        if($request->filled('user_id')) {
            dispatch(new CustomerCardUpdatedByAdminMailJob($user->id));
        } else {
            dispatch(new CustomerCardUpdatedMailJob($user->id));
        }

        return response()->json([
            "error" => 0,
            "data" => $user_card,
            "message" => "Card has been set to default successfully."
        ], 200);
    }

    public function get_invoices(Request $request) {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $order = (object) [
            'orderby' => $request->filled('orderby') ? $request->orderby : 'id',
            'order' => $request->filled('order') ? $request->order : 'DESC',
        ];

        $invoiceQuery = SubscriptionInvoices::where('user_id', $user->id)->filterInvoices($request)->orderBy($order->orderby, $order->order);

        if ($request->filled('perpage')) {
            $invoices = $invoiceQuery->paginate($request->perpage);
        } else {
            if($request->filled('limit')) {
                $invoiceQuery->limit($request->limit);
            }
            $invoices = $invoiceQuery->get();
        }

        $invoices->load('user', 'package', 'subscription', 'coupon');

        $response = [
            'error' => 0,
            'data' => $request->filled('perpage') ? $invoices->items() : $invoices,
            'message' => 'Invoices have been successfully retrieved.'
        ];

        if($request->filled('perpage')) {
            $response['paginate'] = $this->paginate($invoices);
        }

        return response()->json($response, 200);
    }

    public function get_subscription(Request $request) {
        $user = $this->resolveUser($request);

        $subscriptionQuery = Subscription::where('user_id', $user->id)->orderBy('id', 'DESC');

        if($request->filled('id')) {
            $subscriptionQuery->whereId($request->id);
        }

        if($request->filled('status')) {
            $subscriptionQuery->status($request->status);
        }

        $subscription = $subscriptionQuery->first();
        if (is_null($subscription)) {
            throw new HttpResponseException(response()->json([
                "error" => 1,
                "message" => "Subscription Not Found!"
            ], 404));
        }

        return response()->json([
            "error" => 0,
            "data" => $subscription->load('user', 'package', 'coupon', 'websites'),
            "message" => "Subscription"
        ], 200);
    }

    public function current_subscription(Request $request) {
        $user = $this->resolveUser($request);
        $subscription = $this->getSubscription($user, ['active', 'trialing', 'past_due', 'paused', 'unpaid']);

        return response()->json([
            "error" => 0,
            "data" => $subscription->load('user', 'package', 'coupon', 'websites'),
            "message" => "Current Subscription"
        ], 200);
    }

    public function pause_subscription(Request $request) {
        $user = $this->resolveUser($request);
        $subscription = $this->getSubscription($user, ['active', 'trialing']);

        if(! is_null($subscription)) {
            if($subscription->pm_subscription_id) {
                $stripe_subscription = $this->stripe->subscriptions->retrieve($subscription->pm_subscription_id);
                if($stripe_subscription) {
                    $stripe_subscription = $this->stripe->subscriptions->update($subscription->pm_subscription_id, [
                        'pause_collection' => [
                            'behavior' => 'mark_uncollectible'
                        ]
                    ]);

                    try {
                        DB::beginTransaction();

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

                        if($request->filled('user_id')) {
                            dispatch(new SubscriptionPausedByAdminMailJob($user->id, $subscription->id));
                        } else {
                            dispatch(new SubscriptionPausedMailJob($user->id, $subscription->id));
                        }

                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error("Error: ". $e->getMessage());
                        return response()->json([
                            "error" => 1,
                            "message" => "Error: ". $e->getMessage()
                        ], 404);
                    }
                }
            }
        }

        $user->load('customer_details');

        return response()->json([
            "error" => 0,
            "data" => [
                'user' => $user,
                'subscription' => $subscription->load('user', 'package', 'coupon', 'websites')
            ],
            "message" => "Your Subscription Has Been Paused"
        ], 200);
    }

    public function resume_subscription(Request $request) {
        $user = $this->resolveUser($request);
        $subscription = $this->getSubscription($user, ['paused']);

        if(! is_null($subscription)) {
            if($subscription->pm_subscription_id) {
                $stripe_subscription = $this->stripe->subscriptions->retrieve($subscription->pm_subscription_id);
                if($stripe_subscription) {
                    $stripe_subscription = $this->stripe->subscriptions->resume($subscription->pm_subscription_id, [
                        'billing_cycle_anchor' => 'now'
                    ]);

                    try {
                        DB::beginTransaction();

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

                        if(count($stripe_invoices->data)) {
                            $stripe_invoice = $stripe_invoices->data[0];
                            $subscription->invoices()->create([
                                'user_id' => $user->id,
                                'package_id' => $subscription->package->id,
                                'subscription_id' => $subscription->id,
                                'pm_invoice_id' => $stripe_invoice->id,
                                'title' => $subscription->name,
                                'description' => $subscription->name. ' ('. Carbon::createFromTimestamp($stripe_subscription->current_period_start)->format('M d') .' - '. Carbon::createFromTimestamp($stripe_subscription->current_period_end)->format('M d Y') .')',
                                'amount' => $stripe_invoice->amount_paid / 100,
                                'status' => $stripe_invoice->status,
                                'date' => Carbon::createFromTimestamp($stripe_invoice->created)->toDateTimeString()
                            ]);
                        }

                        $user->license()->update([
                            'status' => 'active'
                        ]);

                        if($request->filled('user_id')) {
                            dispatch(new SubscriptionResumedByAdminMailJob($user->id, $subscription->id));
                        } else {
                            dispatch(new SubscriptionResumedMailJob($user->id, $subscription->id));
                        }

                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error("Error: ". $e->getMessage());
                        return response()->json([
                            "error" => 1,
                            "message" => "Error: ". $e->getMessage()
                        ], 404);
                    }
                }
            }
        }

        $user->load('customer_details');

        return response()->json([
            "error" => 0,
            "data" => [
                'user' => $user,
                'subscription' => $subscription->load('user', 'package', 'coupon', 'websites')
            ],
            "message" => "Your Subscription Has Been Resumed"
        ], 200);
    }

    public function payment(SubscriptionPaymentRequest $request) {
        $user = $this->resolveUser($request);

        $package = $this->getPackage($request);
        $coupon = $this->handleCoupon($user, $request);

        $stripe_customer = null;
        $stripe_subscription = null;
        $stripe_card = null;
        $payment_token = null;

        if(!$user->customer_details()->exists()) {
            $user->customer_details()->create([
                'is_avail_trial' => 0,
                'is_avail_free_plan' => 0,
                'auto_renewal_subscription' => 1,
                'pm_customer_id' => null,
            ]);
        }

        if(!$package->free_plan) {
            if($request->payment_method == 'stripe') {
                if($request->default_card === false) {
                    if($request->paymentMethodId == '') {
                        return response()->json([
                            "error" => 1,
                            "message" => "Token Not Found!"
                        ], 404);
                    }
                }

                $default_card = $user->payment_cards()->where('is_default', 1)->first();
                $payment_token = $request->paymentMethodId;
                if(!is_null($default_card)) {
                    $payment_token = ($request->default_card === false) ? $request->paymentMethodId : $default_card->pm_id;
                }

                $stripe_customer = $this->createOrUpdateStripeCustomer($user, $payment_token, $request);
                $stripe_subscription = $this->createStripeSubscription($user, $package, $coupon, $stripe_customer, $payment_token);
                if($request->default_card === false) {
                    $stripe_card = $this->getStripeCard($payment_token);
                }
            }
        }

        if($request->payment_method == 'stripe') {
            $subscription = $this->handleCreateStripeSubscriptionData($user, $package, $coupon, $stripe_customer, $stripe_subscription, $stripe_card, $payment_token, $request);
        }

        $user->load('customer_details');

        return response()->json([
            "error" => 0,
            "data" => [
                'subscription' => $subscription->load('user', 'package', 'coupon', 'websites'),
                'user' => $user
            ],
            "message" => "Thanks for Subscribing!"
        ], 200);
    }

    public function upgrade_subscription(SubscriptionUpgradeRequest $request) {
        $user = $this->resolveUser($request);

        $package = $this->getPackage($request);
        $coupon = $this->handleCoupon($user, $request);
        $current_subscription = $this->getSubscription($user, ['active', 'trialing', 'past_due', 'paused', 'unpaid']);

        $stripe_customer = null;
        $stripe_subscription = null;
        $stripe_card = null;
        $payment_token = null;

        if(!$user->customer_details()->exists()) {
            $user->customer_details()->create([
                'is_avail_trial' => 0,
                'is_avail_free_plan' => 0,
                'auto_renewal_subscription' => 1,
                'pm_customer_id' => null,
            ]);
        }

        if(!$package->free_plan) {
            if($request->payment_method == 'stripe') {
                if($request->default_card === false) {
                    if($request->paymentMethodId == '') {
                        return response()->json([
                            "error" => 1,
                            "message" => "Token Not Found!"
                        ], 404);
                    }
                }

                $default_card = $user->payment_cards()->where('is_default', 1)->first();
                $payment_token = $request->paymentMethodId;
                if(!is_null($default_card)) {
                    $payment_token = ($request->default_card === false) ? $request->paymentMethodId : $default_card->pm_id;
                }

                $stripe_customer = $this->createOrUpdateStripeCustomer($user, $payment_token, $request);
                if($current_subscription->pm_subscription_id) {
                    if($current_subscription->package->duration_lifetime) {
                        $stripe_subscription = $this->createStripeSubscription($user, $package, $coupon, $stripe_customer, $payment_token);
                    } else {
                        $stripe_subscription = $this->upgradeStripeSubscription($user, $package, $coupon, $stripe_customer, $payment_token, $current_subscription);
                    }
                } else {
                    $stripe_subscription = $this->createStripeSubscription($user, $package, $coupon, $stripe_customer, $payment_token);
                }

                if($request->default_card === false) {
                    $stripe_card = $this->getStripeCard($payment_token);
                }
            }
        } else {
            if(!is_null($current_subscription) && $current_subscription->pm_subscription_id != null && !$current_subscription->package->duration_lifetime) {
                try {
                    $stripe_subscription = $this->stripe->subscriptions->cancel($current_subscription->pm_subscription_id);
                } catch (\Exception $exception) {
                    return response()->json([
                        "error" => 1,
                        "message" => "Error: " . $exception->getMessage()
                    ], 404);
                }
            }
        }

        if($request->payment_method == 'stripe') {
            $subscription = $this->handleUpgradeSubscriptionData($user, $package, $coupon, $current_subscription, $stripe_customer, $stripe_subscription, $stripe_card, $payment_token, $request);
        }

        $user->load('customer_details');

        return response()->json([
            "error" => 0,
            "data" => [
                'subscription' => $subscription->load('user', 'package', 'coupon', 'websites'),
                'user' => $user
            ],
            "message" => "Your Subscription Has Been Updated"
        ], 200);
    }

    public function apply_discount(ApplyDiscountRequest $request) {
        $user = $this->resolveUser($request);
        $current_subscription = $this->getSubscription($user, ['active', 'trialing']);
        if($current_subscription->pm_subscription_id == null) {
            return response()->json([
                "error" => 1,
                "message" => "You can't apply a discount."
            ], 404);
        }

        $stripe_subscription = $this->stripe->subscriptions->retrieve($current_subscription->pm_subscription_id);
        if(! $stripe_subscription) {
            return response()->json([
                "error" => 1,
                "message" => "Subscription Not Found!"
            ], 404);
        }

        $coupon = $this->handleCoupon($user, $request);

        try {
            DB::beginTransaction();
            $new_stripe_subscription = $this->stripe->subscriptions->update($current_subscription->pm_subscription_id, [
                'coupon' => $coupon->pm_id
            ]);

            if($coupon->duration == 'once') {
                $coupon_expire_at = Carbon::createFromTimestamp($new_stripe_subscription->current_period_end)->toDateTimeString();
            } else {
                $coupon_expire_at = Carbon::now()->addMonths($coupon->duration_month)->toDateTimeString();
            }

            $current_subscription->update([
                'coupon_id' => $coupon->id,
                'coupon_expire_at' => $coupon_expire_at
            ]);

            $coupon->users()->attach($user->id);

            $stripe_invoices = $this->stripe->invoices->all([
                'subscription' => $stripe_subscription->id,
                'limit' => 1
            ]);

            if(count($stripe_invoices->data)) {
                $stripe_invoice = $stripe_invoices->data[0];
                $invoiceData = [
                    'user_id' => $user->id,
                    'package_id' => $current_subscription->package->id,
                    'subscription_id' => $current_subscription->id,
                    'pm_invoice_id' => $stripe_invoice->id,
                    'title' => $current_subscription->name,
                    'description' => $current_subscription->name. ' ('. Carbon::createFromTimestamp($new_stripe_subscription->current_period_start)->format('M d') .' - '. Carbon::createFromTimestamp($new_stripe_subscription->current_period_end)->format('M d Y') .')',
                    'amount' => $stripe_invoice->amount_paid / 100,
                    'status' => $stripe_invoice->status,
                    'date' => Carbon::createFromTimestamp($stripe_invoice->created)->toDateTimeString()
                ];

                if($coupon != null) {
                    if($coupon->duration == 'once') {
                        $coupon_expire_at = Carbon::createFromTimestamp($new_stripe_subscription->current_period_end)->toDateTimeString();
                    } else {
                        $coupon_expire_at = Carbon::now()->addMonths($coupon->duration_month)->toDateTimeString();
                    }

                    $invoiceData['coupon_id'] = $coupon->id;
                    $invoiceData['coupon_expire_at'] = $coupon_expire_at;
                } else {
                    $invoiceData['coupon_id'] = null;
                    $invoiceData['coupon_expire_at'] = null;
                }

                $current_subscription->invoices()->create($invoiceData);
            }

            $coupon_id = ($coupon != null) ? $coupon->id : null;
            dispatch(new SubscriptionUpdatedMailJob($user->id, $current_subscription->id, $current_subscription->package->id, $coupon_id));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "error" => 1,
                "message" => "Error: ". $e->getMessage()
            ], 400);
        }

        return response()->json([
            "error" => 0,
            "data" => $current_subscription->load('user', 'package', 'coupon', 'websites'),
            "message" => "Discount code has been applied in current subscription"
        ], 200);
    }

    public function change_auto_renewal(Request $request) {
        $user = $this->resolveUser($request);
        $current_subscription = $this->getSubscription($user, ['active', 'trialing', 'past_due', 'paused', 'unpaid']);
        $auto_renewal = $user->customer_details->auto_renewal_subscription;

        try {
            DB::beginTransaction();
            if(! is_null($current_subscription)) {
                if($current_subscription->pm_subscription_id) {
                    $stripe_subscription = $this->stripe->subscriptions->retrieve($current_subscription->pm_subscription_id);
                    if($stripe_subscription) {
                        $stripe_subscription = $this->stripe->subscriptions->update($current_subscription->pm_subscription_id, [
                            'cancel_at_period_end' => $auto_renewal == 1 ? false : true
                        ]);

                        $current_subscription->update([
                            'next_billing_date' => Carbon::createFromTimestamp($stripe_subscription->current_period_end)->toDateTimeString()
                        ]);
                    }
                }
            }

            $user->customer_details()->update([
                'auto_renewal_subscription' => $auto_renewal == 1 ? 0 : 1
            ]);

            if($request->filled('user_id')) {
                if($user->customer_details->auto_renewal_subscription == 1) {
                    dispatch(new CustomerAutoRenewalEnabledByAdminMailJob($user->id));
                } else {
                    dispatch(new CustomerAutoRenewalDisabledByAdminMailJob($user->id));
                }
            } else {
                if($user->customer_details->auto_renewal_subscription == 1) {
                    dispatch(new CustomerAutoRenewalEnabledMailJob($user->id));
                } else {
                    dispatch(new CustomerAutoRenewalDisabledMailJob($user->id));
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "error" => 1,
                "message" => "Error: ". $e->getMessage()
            ], 400);
        }

        $user->load('customer_details');

        return response()->json([
            "error" => 0,
            "data" => [
                'subscription' => $current_subscription->load('user', 'package', 'coupon'),
                'user' => $user
            ],
            "message" => "Auto-Renewal Subscription ". ($user->customer_details->auto_renewal_subscription ? 'Enabled' : 'Disabled')
        ], 200);
    }

    public function cancel_subscription(Request $request) {
        $user = $this->resolveUser($request);
        $current_subscription = $this->getSubscription($user, ['active', 'trialing', 'past_due', 'paused', 'unpaid']);

        try {
            DB::beginTransaction();
            $subscription_data = [
                'status' => 'canceled',
                'ended_at' => now()
            ];

            if($current_subscription->status == 'trialing') {
                $subscription_data['trial_end_at'] = now();
            }

            $current_subscription->update($subscription_data);

            if($current_subscription->pm_subscription_id) {
                $this->stripe->subscriptions->cancel($current_subscription->pm_subscription_id);
            }

            $user->license()->update([
                'status' => 'deactive'
            ]);

            if($request->filled('user_id')) {
                dispatch(new SubscriptionCancelledByAdminMailJob($user->id, $current_subscription->id));
            } else {
                dispatch(new SubscriptionCancelledMailJob($user->id, $current_subscription->id));
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "error" => 1,
                "message" => "Error: ". $e->getMessage()
            ], 400);
        }

        $user->load('customer_details');

        return response()->json([
            "error" => 0,
            "data" => [
                'user' => $user
            ],
            "message" => "Subscription has been successfully canceled"
        ], 200);
    }

    public function getSubscription($user, $status) {
        $subscription = Subscription::where('user_id', $user->id)->orderby('id', 'desc')->status($status)->first();
        if (is_null($subscription)) {
            throw new HttpResponseException(response()->json([
                "error" => 1,
                "message" => "Subscription Not Found!"
            ], 404));
        }

        return $subscription;
    }

    public function getPackage($request) {
        $package = Package::whereId($request->package)->where('status', 'active')->first();
        if (is_null($package)) {
            throw new HttpResponseException(response()->json([
                "error" => 1,
                "message" => "Package Not Found!"
            ], 404));
        }

        return $package;
    }

    public function handleCoupon($user, $request) {
        if($request->discount_code == '') {
            return null;
        }

        $coupon = Coupon::where('code', $request->discount_code)->first();
        if(is_null($coupon)) {
            throw new HttpResponseException(response()->json([
                "error" => 1,
                "data" => [
                    'discount_code' => [
                        "Invalid Discount Code!"
                    ]
                ],
                "message" => "Validation Errors Found!"
            ], 422));
        }

        if(is_null($coupon->pm_coupon_id)) {
            throw new HttpResponseException(response()->json([
                "error" => 1,
                "data" => [
                    'discount_code' => [
                        "Invalid Discount Code!"
                    ]
                ],
                "message" => "Validation Errors Found!"
            ], 422));
        }

        if($coupon->expires_at <= now()) {
            throw new HttpResponseException(response()->json([
                "error" => 1,
                "data" => [
                    'discount_code' => [
                        "Your Discount Code is Expired!"
                    ]
                ],
                "message" => "Validation Errors Found!"
            ], 422));
        }

        if(! $coupon->users()->exists()) {
            return $coupon;
        }

        if($coupon->users()->count() >= $coupon->max_uses) {
            throw new HttpResponseException(response()->json([
                "error" => 1,
                "data" => [
                    'discount_code' => [
                        "Your Discount Code Limit Exceeded!"
                    ]
                ],
                "message" => "Validation Errors Found!"
            ], 422));
        }

        $coupon_user_count = $coupon->whereHas('users', function($query) use($user) {
            $query->where('users.id', $user->id);
        })->count();

        if($coupon_user_count >= $coupon->max_uses_user) {
            throw new HttpResponseException(response()->json([
                "error" => 1,
                "data" => [
                    'discount_code' => [
                        "Your Discount Code Limit Exceeded!"
                    ]
                ],
                "message" => "Validation Errors Found!"
            ], 422));
        }

        return $coupon;
    }

    public function createOrUpdateStripeCustomer($user, $payment_token, $request) {
        try {
            if($user->customer_details->pm_customer_id === null) {
                $stripe_customer = $this->stripe->customers->create([
                    'name' => $user->fullname,
                    'email' => $user->email,
                    'phone' => $user->phone_number,
                    'payment_method' => $payment_token,
                    'invoice_settings' => [
                        'default_payment_method' => $payment_token
                    ]
                ]);

                $user->customer_details()->update([
                    'pm_customer_id' => $stripe_customer->id
                ]);
            } else {
                $stripe_customer = $this->stripe->customers->retrieve($user->customer_details->pm_customer_id);
                if($request->default_card === false) {
                    if($stripe_customer->invoice_settings->default_payment_method != $payment_token) {
                        $this->stripe->paymentMethods->attach($payment_token, [
                            'customer' => $user->customer_details->pm_customer_id
                        ]);

                        $stripe_customer = $this->stripe->customers->update($stripe_customer->id, [
                            'invoice_settings' => [
                                'default_payment_method' => $payment_token
                            ]
                        ]);
                    }
                }
            }

            return $stripe_customer;
        } catch (\Exception $exception) {
            throw new HttpResponseException(response()->json([
                "error" => 1,
                "message" => "Error123: " . $exception->getMessage()
            ], 404));
        }
    }

    public function createStripeSubscription($user, $package, $coupon, $stripe_customer, $payment_token) {
        try {
            if(!$package->duration_lifetime) {
                $package_stripe = $package->payment_methods()->stripe()->first();
                $ssData = [
                    'customer' => $stripe_customer->id,
                    'items' => [
                        ['plan' => $package_stripe->pm_price_id]
                    ]
                ];

                if($package->trial_period_days !== null && $user->customer_details->is_avail_trial === 0) {
                    $ssData['trial_period_days'] = (int) $package->trial_period_days;
                }

                if($coupon != null) {
                    $ssData['coupon'] = $coupon->pm_coupon_id;
                }

                $stripe_subscription = $this->stripe->subscriptions->create($ssData);
            } else {
                $price = $package->price;
                if($coupon != null) {
                    $price = discount_price($package->price, $coupon->amount, $coupon->type);
                }

                $stripe_subscription = $this->stripe->paymentIntents->create([
                    'customer' => $stripe_customer->id,
                    'amount' => $price * 100,
                    'currency' => 'aud',
                    'payment_method_types' => ['card'],
                    'payment_method' => $payment_token,
                    'confirm' => true
                ]);
            }

            return $stripe_subscription;
        } catch (\Exception $exception) {
            throw new HttpResponseException(response()->json([
                "error" => 1,
                "message" => "Error Stripe Subscription: " . $exception->getMessage()
            ], 404));
        }
    }

    public function upgradeStripeSubscription($user, $package, $coupon, $stripe_customer, $payment_token, $current_subscription) {
        try {
            $stripe_subscription = $this->stripe->subscriptions->retrieve($current_subscription->pm_subscription_id);
            if(!$package->duration_lifetime) {
                $package_stripe = $package->payment_methods()->stripe()->first();
                $ssData = [
                    'cancel_at_period_end' => false,
                    'proration_behavior' => 'create_prorations',
                    'items' => [
                        [
                            'id' => $stripe_subscription->items->data[0]->id,
                            'price' => $package_stripe->pm_price_id
                        ],
                    ]
                ];

                if($package->trial_period_days !== null && $user->trial === 0) {
                    $ssData['trial_end'] = 'now';
                }

                if($coupon != null) {
                    $ssData['coupon'] = $coupon->pm_id;
                } else {
                    $ssData['coupon'] = null;
                }

                $stripe_subscription = $this->stripe->subscriptions->update($current_subscription->pm_subscription_id, $ssData);
            } else {
                $subscription_data = [
                    'status' => 'canceled',
                    'ended_at' => now()
                ];

                if($current_subscription->status == 'trialing') {
                    $subscription_data['trial_end_at'] = now();
                }

                $current_subscription->update($subscription_data);

                if($current_subscription->pm_subscription_id) {
                    $this->stripe->subscriptions->cancel($current_subscription->pm_subscription_id);
                }

                $price = $package->price;
                if($coupon != null) {
                    $price = discount_price($package->price, $coupon->amount, $coupon->type);
                }

                $stripe_subscription = $this->stripe->paymentIntents->create([
                    'customer' => $stripe_customer->id,
                    'amount' => $price * 100,
                    'currency' => 'aud',
                    'payment_method_types' => ['card'],
                    'payment_method' => $payment_token,
                    'confirm' => true
                ]);
            }

            return $stripe_subscription;
        } catch (\Exception $exception) {
            throw new HttpResponseException(response()->json([
                "error" => 1,
                "message" => "Error Stripe Subscription: " . $exception->getMessage()
            ], 404));
        }
    }

    public function getStripeCard($payment_token) {
        try {
            return $this->stripe->paymentMethods->retrieve($payment_token);
        } catch (\Exception $exception) {
            throw new HttpResponseException(response()->json([
                "error" => 1,
                "message" => "Error Stripe Card: " . $exception->getMessage()
            ], 404));
        }
    }

    public function handleCreateStripeSubscriptionData($user, $package, $coupon, $stripe_customer, $stripe_subscription, $stripe_card, $payment_token, $request) {
        if($package->free_plan) {
            if($user->customer_details->is_avail_free_plan) {
                throw new HttpResponseException(response()->json([
                    "error" => 1,
                    "message" => "Sorry! You can't avail of a free package."
                ], 422));
            }
        }

        try {
            DB::beginTransaction();
            $package_pm = $package->payment_methods()->stripe()->first();

            if(!$package->free_plan) {
                if(!$package->duration_lifetime) {
                    $amount = 0;
                    foreach ($stripe_subscription->items->data as $item) {
                        $amount += $item->plan->amount / 100;
                    }

                    $ssData = [
                        'user_id' => $user->id,
                        'package_id' => $package->id,
                        'pm_subscription_id' => $stripe_subscription->id,
                        'pm_customer_id' => $stripe_customer->id,
                        'pm_plan_id' => $package_pm->pm_price_id,
                        'pm_id' => $payment_token,
                        'payment_method' => $request->payment_method,
                        'name' => $package->title,
                        'amount' => $amount,
                        'start_at' => Carbon::createFromTimestamp($stripe_subscription->current_period_start)->toDateTimeString(),
                        'next_billing_date' => Carbon::createFromTimestamp($stripe_subscription->current_period_end)->toDateTimeString(),
                        'ended_at' => $stripe_subscription->ended_at ? Carbon::createFromTimestamp($stripe_subscription->ended_at)->toDateTimeString() : null,
                        'trial_end_at' => null,
                        'resumes_at' => null,
                        'paused_at' => null,
                        'status' => $stripe_subscription->status,
                        'leads' => 0,
                        'payload' => json_encode($stripe_subscription)
                    ];

                    if($package->trial_period_days !== null && $user->customer_details->is_avail_trial === 0) {
                        if($stripe_subscription->trial_start) {
                            $ssData['trial_start_at'] = Carbon::createFromTimestamp($stripe_subscription->trial_start)->toDateTimeString();
                        }
                    }

                    if($coupon != null) {
                        if($coupon->duration == 'once') {
                            $coupon_expire_at = Carbon::createFromTimestamp($stripe_subscription->current_period_end)->toDateTimeString();
                        } else {
                            $coupon_expire_at = Carbon::now()->addMonths($coupon->duration_month)->toDateTimeString();
                        }

                        $ssData['coupon_id'] = $coupon->id;
                        $ssData['coupon_expire_at'] = $coupon_expire_at;
                    } else {
                        $ssData['coupon_id'] = null;
                        $ssData['coupon_expire_at'] = null;
                    }
                    $subscription = Subscription::create($ssData);

                    $stripe_invoices = $this->stripe->invoices->all([
                        'subscription' => $stripe_subscription->id,
                        'limit' => 1
                    ]);

                    if(count($stripe_invoices->data)) {
                        $stripe_invoice = $stripe_invoices->data[0];
                        $invoiceData = [
                            'user_id' => $user->id,
                            'package_id' => $subscription->package->id,
                            'subscription_id' => $subscription->id,
                            'pm_invoice_id' => $stripe_invoice->id,
                            'title' => $subscription->name,
                            'description' => $subscription->name. ' ('. Carbon::createFromTimestamp($stripe_subscription->current_period_start)->format('M d') .' - '. Carbon::createFromTimestamp($stripe_subscription->current_period_end)->format('M d Y') .')',
                            'amount' => $stripe_invoice->amount_paid / 100,
                            'status' => $stripe_invoice->status,
                            'date' => Carbon::createFromTimestamp($stripe_invoice->created)->toDateTimeString()
                        ];

                        if($coupon != null) {
                            if($coupon->duration == 'once') {
                                $coupon_expire_at = Carbon::createFromTimestamp($stripe_subscription->current_period_end)->toDateTimeString();
                            } else {
                                $coupon_expire_at = Carbon::now()->addMonths($coupon->duration_month)->toDateTimeString();
                            }

                            $invoiceData['coupon_id'] = $coupon->id;
                            $invoiceData['coupon_expire_at'] = $coupon_expire_at;
                        } else {
                            $invoiceData['coupon_id'] = null;
                            $invoiceData['coupon_expire_at'] = null;
                        }

                        $subscription->invoices()->create($invoiceData);
                    }
                } else {
                    $ssData = [
                        'user_id' => $user->id,
                        'package_id' => $package->id,
                        'pm_subscription_id' => $stripe_subscription->id,
                        'pm_customer_id' => $stripe_customer->id,
                        'pm_plan_id' => null,
                        'pm_id' => $payment_token,
                        'payment_method' => $request->payment_method,
                        'name' => $package->title,
                        'amount' => $stripe_subscription->amount / 100,
                        'start_at' => Carbon::createFromTimestamp($stripe_subscription->created)->toDateTimeString(),
                        'ended_at' => null,
                        'next_billing_date' => null,
                        'trial_start_at' => null,
                        'trial_end_at' => null,
                        'resumes_at' => null,
                        'paused_at' => null,
                        'status' => 'active',
                        'leads' => 0,
                        'payload' => json_encode($stripe_subscription)
                    ];

                    if($coupon != null) {
                        $ssData['coupon_id'] = $coupon->id;
                        $ssData['coupon_expire_at'] = null;
                    }
                    $subscription = Subscription::create($ssData);

                    $invoiceData = [
                        'user_id' => $user->id,
                        'package_id' => $subscription->package->id,
                        'subscription_id' => $subscription->id,
                        'pm_invoice_id' => null,
                        'title' => $subscription->name,
                        'description' => $subscription->name. ' ('. Carbon::createFromTimestamp($stripe_subscription->created)->format('M d Y') .' - Lifetime )',
                        'amount' => $stripe_subscription->amount / 100,
                        'status' => 'paid',
                        'date' => Carbon::createFromTimestamp($stripe_subscription->created)->toDateTimeString()
                    ];

                    if($coupon != null) {
                        $invoiceData['coupon_id'] = $coupon->id;
                        $invoiceData['coupon_expire_at'] = null;
                    }
                    $subscription->invoices()->create($invoiceData);
                }

                if($coupon != null) {
                    $coupon->users()->attach($user->id);
                }

                $customer_data = [];
                if(!is_null($stripe_customer)) {
                    $customer_data['pm_customer_id'] = $stripe_customer->id;
                }

                if($package->trial_period_days !== null && $user->customer_details->is_avail_trial === 0) {
                    $customer_data['is_avail_trial'] = 1;
                }

                $user->customer_details()->update($customer_data);

                if($request->default_card === false) {
                    $user->payment_cards()->where('is_default', 1)->update([
                        'is_default' => 0
                    ]);

                    $user_card = $user->payment_cards()->where('brand', $stripe_card->card->brand)->where('last4', $stripe_card->card->last4)->first();
                    if(is_null($user_card)) {
                        $user->payment_cards()->create([
                            'card_holder_name' => $request->card_holder_name,
                            'brand' => $stripe_card->card->brand,
                            'last4' => $stripe_card->card->last4,
                            'exp_month' => $stripe_card->card->exp_month,
                            'exp_year' => $stripe_card->card->exp_year,
                            'is_default' => 1,
                            'pm_id' => $payment_token
                        ]);
                    } else {
                        $user_card->update([
                            'card_holder_name' => $request->card_holder_name,
                            'brand' => $stripe_card->card->brand,
                            'last4' => $stripe_card->card->last4,
                            'exp_month' => $stripe_card->card->exp_month,
                            'exp_year' => $stripe_card->card->exp_year,
                            'is_default' => 1,
                            'pm_id' => $payment_token
                        ]);
                    }
                }
            } else {
                $ended_at = now();
                switch ($package->duration_type) {
                    case 'day':
                        $ended_at->addDays($package->duration);
                        break;
                    case 'week':
                        $ended_at->addWeeks($package->duration);
                        break;
                    case 'month':
                        $ended_at->addMonths($package->duration);
                        break;
                    case 'year':
                        $ended_at->addYears($package->duration);
                        break;
                }

                $ssData = [
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                    'coupon_id' => null,
                    'coupon_expire_at' => null,
                    'pm_subscription_id' => null,
                    'pm_customer_id' => null,
                    'pm_plan_id' => null,
                    'pm_id' => null,
                    'name' => $package->title,
                    'amount' => 0,
                    'payment_method' => null,
                    'start_at' => now(),
                    'ended_at' => $ended_at->toDateTimeString(),
                    'next_billing_date' => null,
                    'trial_start_at' => null,
                    'trial_end_at' => null,
                    'resumes_at' => null,
                    'paused_at' => null,
                    'status' => 'active',
                    'leads' => 0,
                    'payload' => null
                ];

                $user->customer_details()->update(['is_avail_free_plan' => true]);
                $subscription = Subscription::create($ssData);

                $date = now();
                switch ($package->duration_type) {
                    case 'day':
                        $date->addDays($package->duration);
                        break;
                    case 'week':
                        $date->addWeeks($package->duration);
                        break;
                    case 'month':
                        $date->addMonths($package->duration);
                        break;
                    case 'year':
                        $date->addYears($package->duration);
                        break;
                }

                $invoiceData = [
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                    'subscription_id' => $subscription->id,
                    'coupon_id' => null,
                    'coupon_expire_at' => null,
                    'pm_invoice_id' => null,
                    'title' => $package->title,
                    'description' => $package->title. ' ('. now()->format('M d') .' - '. $date->format('M d Y') .')',
                    'amount' => 0,
                    'status' => 'paid',
                    'date' => now()
                ];
                $subscription->invoices()->create($invoiceData);
            }

            $websites_ids = $request->websites;
            if($subscription->package->website_limit) {
                $websites_ids = array_slice($websites_ids, 0, $subscription->package->website_limit);
            }

            $subscription->websites()->sync($websites_ids);
            $subscription->websites()->update([
                'status' => 'active'
            ]);

            if($subscription->status == 'active' || $subscription->status == 'trialing') {
                $user->license()->update([
                    'status' => 'active'
                ]);
            } else {
                $user->license()->update([
                    'status' => 'deactive'
                ]);
            }

            if($request->filled('uuid')) {
                $user_payment_link = UserPaymentLink::where('user_id', $user->id)->where('uuid', $request->uuid)->first();
                if(! is_null($user_payment_link)) {
                    $user_payment_link->delete();
                }
            }

            $coupon_id = ($coupon != null) ? $coupon->id : null;
            if($request->filled('user_id')) {
                dispatch(new SubscriptionCreatedByAdminMailJob($user->id, $subscription->id, $package->id, $coupon_id));
            } else {
                dispatch(new SubscriptionCreatedMailJob($user->id, $subscription->id, $package->id, $coupon_id));
            }

            DB::commit();
            return $subscription;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpResponseException(response()->json([
                "error" => 1,
                "message" => "Error: " . $exception->getMessage()
            ], 404));
        }
    }

    public function handleUpgradeSubscriptionData($user, $package, $coupon, $subscription, $stripe_customer, $stripe_subscription, $stripe_card, $payment_token, $request) {
        if($package->free_plan) {
            if($user->customer_details->is_avail_free_plan) {
                throw new HttpResponseException(response()->json([
                    "error" => 1,
                    "message" => "Sorry! You can't avail of a free package."
                ], 422));
            }
        }

        try {
            DB::beginTransaction();
            $package_pm = $package->payment_methods()->stripe()->first();
            if(!$package->free_plan) {
                if(!$package->duration_lifetime) {
                    $amount = 0;
                    foreach ($stripe_subscription->items->data as $item) {
                        $amount += $item->plan->amount / 100;
                    }

                    $ssData = [
                        'package_id' => $package->id,
                        'pm_subscription_id' => $stripe_subscription->id,
                        'pm_customer_id' => $stripe_customer->id,
                        'pm_plan_id' => $package_pm->pm_price_id,
                        'pm_id' => $payment_token,
                        'payment_method' => $request->payment_method,
                        'name' => $package->title,
                        'amount' => $amount,
                        'start_at' => Carbon::createFromTimestamp($stripe_subscription->current_period_start)->toDateTimeString(),
                        'next_billing_date' => Carbon::createFromTimestamp($stripe_subscription->current_period_end)->toDateTimeString(),
                        'ended_at' => $stripe_subscription->ended_at ? Carbon::createFromTimestamp($stripe_subscription->ended_at)->toDateTimeString() : null,
                        'trial_end_at' => null,
                        'resumes_at' => null,
                        'paused_at' => null,
                        'status' => $stripe_subscription->status,
                        'leads' => 0,
                        'payload' => json_encode($stripe_subscription)
                    ];

                    if($package->trial_period_days !== null && $user->customer_details->is_avail_trial === 0) {
                        if($stripe_subscription->trial_start) {
                            $ssData['trial_start_at'] = Carbon::createFromTimestamp($stripe_subscription->trial_start)->toDateTimeString();
                        }
                    }

                    if($coupon != null) {
                        if($coupon->duration == 'once') {
                            $coupon_expire_at = Carbon::createFromTimestamp($stripe_subscription->current_period_end)->toDateTimeString();
                        } else {
                            $coupon_expire_at = Carbon::now()->addMonths($coupon->duration_month)->toDateTimeString();
                        }

                        $ssData['coupon_id'] = $coupon->id;
                        $ssData['coupon_expire_at'] = $coupon_expire_at;
                    } else {
                        $ssData['coupon_id'] = null;
                        $ssData['coupon_expire_at'] = null;
                    }
                    $subscription->update($ssData);

                    $stripe_invoices = $this->stripe->invoices->all([
                        'subscription' => $stripe_subscription->id,
                        'limit' => 1
                    ]);

                    if(count($stripe_invoices->data)) {
                        $stripe_invoice = $stripe_invoices->data[0];
                        $invoiceData = [
                            'user_id' => $user->id,
                            'package_id' => $subscription->package->id,
                            'subscription_id' => $subscription->id,
                            'pm_invoice_id' => $stripe_invoice->id,
                            'title' => $subscription->name,
                            'description' => $subscription->name. ' ('. Carbon::createFromTimestamp($stripe_subscription->current_period_start)->format('M d') .' - '. Carbon::createFromTimestamp($stripe_subscription->current_period_end)->format('M d Y') .')',
                            'amount' => $stripe_invoice->amount_paid / 100,
                            'status' => $stripe_invoice->status,
                            'date' => Carbon::createFromTimestamp($stripe_invoice->created)->toDateTimeString()
                        ];

                        if($coupon != null) {
                            if($coupon->duration == 'once') {
                                $coupon_expire_at = Carbon::createFromTimestamp($stripe_subscription->current_period_end)->toDateTimeString();
                            } else {
                                $coupon_expire_at = Carbon::now()->addMonths($coupon->duration_month)->toDateTimeString();
                            }

                            $invoiceData['coupon_id'] = $coupon->id;
                            $invoiceData['coupon_expire_at'] = $coupon_expire_at;
                        } else {
                            $invoiceData['coupon_id'] = null;
                            $invoiceData['coupon_expire_at'] = null;
                        }

                        $subscription->invoices()->create($invoiceData);
                    }
                } else {
                    $ssData = [
                        'user_id' => $user->id,
                        'package_id' => $package->id,
                        'pm_subscription_id' => $stripe_subscription->id,
                        'pm_customer_id' => $stripe_customer->id,
                        'pm_plan_id' => null,
                        'pm_id' => $payment_token,
                        'payment_method' => $request->payment_method,
                        'name' => $package->title,
                        'amount' => $stripe_subscription->amount / 100,
                        'start_at' => Carbon::createFromTimestamp($stripe_subscription->created)->toDateTimeString(),
                        'next_billing_date' => null,
                        'trial_start_at' => null,
                        'trial_end_at' => null,
                        'ended_at' => null,
                        'resumes_at' => null,
                        'paused_at' => null,
                        'status' => 'active',
                        'leads' => 0,
                        'payload' => json_encode($stripe_subscription)
                    ];

                    if($coupon != null) {
                        $ssData['coupon_id'] = $coupon->id;
                        $ssData['coupon_expire_at'] = null;
                    }
                    $subscription = Subscription::create($ssData);

                    $invoiceData = [
                        'user_id' => $user->id,
                        'package_id' => $subscription->package->id,
                        'subscription_id' => $subscription->id,
                        'pm_invoice_id' => null,
                        'title' => $subscription->name,
                        'description' => $subscription->name. ' ('. Carbon::createFromTimestamp($stripe_subscription->created)->format('M d Y') .' - Lifetime )',
                        'amount' => $stripe_subscription->amount / 100,
                        'status' => 'paid',
                        'date' => Carbon::createFromTimestamp($stripe_subscription->created)->toDateTimeString()
                    ];

                    if($coupon != null) {
                        $invoiceData['coupon_id'] = $coupon->id;
                        $invoiceData['coupon_expire_at'] = null;
                    }
                    $subscription->invoices()->create($invoiceData);
                }

                if($coupon != null) {
                    $coupon->users()->attach($user->id);
                }

                $customer_data = [];
                if(!is_null($stripe_customer)) {
                    $customer_data['pm_customer_id'] = $stripe_customer->id;
                }

                if($package->trial_period_days !== null && $user->customer_details->is_avail_trial === 0) {
                    $customer_data['is_avail_trial'] = 1;
                }
                $user->customer_details()->update($customer_data);

                if($request->default_card === false) {
                    $user->payment_cards()->where('is_default', 1)->update([
                        'is_default' => 0
                    ]);

                    $user_card = $user->payment_cards()->where('brand', $stripe_card->card->brand)->where('last4', $stripe_card->card->last4)->first();
                    if(is_null($user_card)) {
                        $user->payment_cards()->create([
                            'card_holder_name' => $request->card_holder_name,
                            'brand' => $stripe_card->card->brand,
                            'last4' => $stripe_card->card->last4,
                            'exp_month' => $stripe_card->card->exp_month,
                            'exp_year' => $stripe_card->card->exp_year,
                            'is_default' => 1,
                            'pm_id' => $payment_token
                        ]);
                    } else {
                        $user_card->update([
                            'card_holder_name' => $request->card_holder_name,
                            'brand' => $stripe_card->card->brand,
                            'last4' => $stripe_card->card->last4,
                            'exp_month' => $stripe_card->card->exp_month,
                            'exp_year' => $stripe_card->card->exp_year,
                            'is_default' => 1,
                            'pm_id' => $payment_token
                        ]);
                    }
                }
            } else {
                $ended_at = now();
                switch ($package->duration_type) {
                    case 'day':
                        $ended_at->addDays($package->duration);
                        break;
                    case 'week':
                        $ended_at->addWeeks($package->duration);
                        break;
                    case 'month':
                        $ended_at->addMonths($package->duration);
                        break;
                    case 'year':
                        $ended_at->addYears($package->duration);
                        break;
                }

                $ssData = [
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                    'coupon_id' => null,
                    'coupon_expire_at' => null,
                    'pm_subscription_id' => null,
                    'pm_customer_id' => null,
                    'pm_plan_id' => null,
                    'pm_id' => null,
                    'name' => $package->title,
                    'amount' => 0,
                    'payment_method' => null,
                    'start_at' => now(),
                    'ended_at' => $ended_at->toDateTimeString(),
                    'next_billing_date' => null,
                    'trial_start_at' => null,
                    'trial_end_at' => null,
                    'resumes_at' => null,
                    'paused_at' => null,
                    'status' => 'active',
                    'leads' => 0,
                    'payload' => null
                ];

                $user->customer_details()->update(['is_avail_free_plan' => true]);
                $subscription->update($ssData);

                $date = now();
                switch ($package->duration_type) {
                    case 'day':
                        $date->addDays($package->duration);
                        break;
                    case 'week':
                        $date->addWeeks($package->duration);
                        break;
                    case 'month':
                        $date->addMonths($package->duration);
                        break;
                    case 'year':
                        $date->addYears($package->duration);
                        break;
                }

                $invoiceData = [
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                    'subscription_id' => $subscription->id,
                    'coupon_id' => null,
                    'coupon_expire_at' => null,
                    'pm_invoice_id' => null,
                    'title' => $package->title,
                    'description' => $package->title. ' ('. now()->format('M d') .' - '. $date->format('M d Y') .')',
                    'amount' => 0,
                    'status' => 'paid',
                    'date' => now()
                ];
                $subscription->invoices()->create($invoiceData);
            }

            $websites_ids = $request->websites;
            if($subscription->package->website_limit) {
                $websites_ids = array_slice($websites_ids, 0, $subscription->package->website_limit);
            }

            $subscription->websites()->sync($websites_ids);
            $subscription->websites()->update([
                'status' => 'active'
            ]);

            if($subscription->status == 'active' || $subscription->status == 'trialing') {
                $user->license()->update([
                    'status' => 'active'
                ]);
            } else {
                $user->license()->update([
                    'status' => 'deactive'
                ]);
            }

            if($request->filled('uuid')) {
                $user_payment_link = UserPaymentLink::where('user_id', $user->id)->where('uuid', $request->uuid)->first();
                if(! is_null($user_payment_link)) {
                    $user_payment_link->delete();
                }
            }

            $coupon_id = ($coupon != null) ? $coupon->id : null;
            if($request->filled('user_id')) {
                dispatch(new SubscriptionUpdatedByAdminMailJob($user->id, $subscription->id, $package->id, $coupon_id));
            } else {
                dispatch(new SubscriptionUpdatedMailJob($user->id, $subscription->id, $package->id, $coupon_id));
            }

            DB::commit();
            return $subscription;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpResponseException(response()->json([
                "error" => 1,
                "message" => "Error: " . $exception->getMessage()
            ], 404));
        }
    }

    public function website_update(SubscriptionWebsitesUpdateRequest $request) {
        $user = $this->resolveUser($request);
        $current_subscription = $this->getSubscription($user, ['active', 'trialing']);

        $websites_ids = $request->websites;
        if($current_subscription->package->website_limit) {
            $websites_ids = array_slice($websites_ids, 0, $current_subscription->package->website_limit);
        }

        $current_subscription->websites()->sync($websites_ids);
        $current_subscription->websites()->update([
            'status' => 'active'
        ]);

        return response()->json([
            "error" => 0,
            "data" => $current_subscription->websites,
            "message" => "Websites has been updated in current subscription"
        ], 200);
    }

    public function get_license(Request $request) {
        $user = $this->resolveUser($request);

        return response()->json([
            "error" => 0,
            "data" => $user->license,
            "message" => "Your license key has been successfully retrieved"
        ], 200);
    }
}
