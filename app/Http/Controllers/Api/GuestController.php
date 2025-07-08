<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\{
    User,
    Package,
    Website,
    Subscription,
    FeedBack,
    Coupon
};
use App\Jobs\{
    SubscriptionCreatedMailJob,
    LoginMailJob,
    WelcomeMailJob,
    FeedBackMailJob,
    SignupMailJob
};
use App\Http\Requests\{
    NewSubscriptionRequest,
    FeedBackRequest
};

use Stripe\StripeClient;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GuestController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function get_packages(Request $request)
    {
        $order = (object) [
            'orderby' => $request->filled('orderby') ? $request->orderby : 'id',
            'order' => $request->filled('order') ? $request->order : 'DESC',
        ];

        $packageQuery = Package::filterPackages($request)->orderBy($order->orderby, $order->order);

        if($request->filled('limit')) {
            $packageQuery->limit($request->limit);
        }

        $packages = $packageQuery->get();
        $packages->load('payment_methods');
        $response = [
            "error" => 0,
            "data" => $packages,
            "message" => "Packages have been successfully retrieved"
        ];

        return response()->json($response, 200);
    }

    public function validate_subscription(Request $request)
    {
        dd($request->all());
        $package = Package::whereId($request->package)->status('active')->first();
        if (is_null($package)) {
            return response()->json([
                "error" => 1,
                "message" => "Package Not Found!"
            ], 404);
        }
        $rules = [
            "email" => "required|unique:users,email",
            "password" => "required|min:8",
            "package" => "required",
            "websites" => "required",
            "websites.*.website_name" => "required|unique:websites,website_name",
            "websites.*.website_url" => "required|unique:websites,website_url",
            "payment_method" => "required"
        ];

        if(!$package->free_plan) {
            $rules['card_holder_name'] = 'required';
        } else {
            $rules['fullname'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                "error" => 1,
                "data" => $validator->errors(),
                "message" => "Validation Errors Found!"
            ], 422);
        }
        if(!$package->free_plan && $request->discount_code != '') {
            $coupon = Coupon::where('code', $request->discount_code)->first();
            if(is_null($coupon)) {
                return response()->json([
                    "error" => 1,
                    "data" => [
                        'discount_code' => [
                            "Invalid Discount Code!"
                        ]
                    ],
                    "message" => "Validation Errors Found!"
                ], 422);
            }

            if(is_null($coupon->pm_coupon_id)) {
                return response()->json([
                    "error" => 1,
                    "data" => [
                        'discount_code' => [
                            "Invalid Discount Code!"
                        ]
                    ],
                    "message" => "Validation Errors Found!"
                ], 422);
            }

            if($coupon->expires_at <= now()) {
                return response()->json([
                    "error" => 1,
                    "data" => [
                        'discount_code' => [
                            "Your Discount Code is Expired!"
                        ]
                    ],
                    "message" => "Validation Errors Found!"
                ], 422);
            }

            if($coupon->users()->exists()) {
                if($coupon->users()->count() >= $coupon->max_uses) {
                    return response()->json([
                        "error" => 1,
                        "data" => [
                            'discount_code' => [
                                "Your Discount Code Limit Exceeded!"
                            ]
                        ],
                        "message" => "Validation Errors Found!"
                    ], 422);
                }
            }
        }

        return response()->json([
            "error" => 0,
            "message" => "success"
        ], 200);
    }

    public function create_subscription(NewSubscriptionRequest $request)
    {
        $package = Package::whereId($request->package)->status('active')->first();
        $user = User::where('email', $request->email)->first();
        $price = $package->price;
        $gst = $price * 0.10;
        $priceWithGST = $price + $gst;
        if (!is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Email already exists!"
            ], 409);
        }

        $coupon = null;
        if(!$package->free_plan && $request->discount_code != '') {
            $coupon = Coupon::where('code', $request->discount_code)->first();
            if(is_null($coupon)) {
                return response()->json([
                    "error" => 1,
                    "data" => [
                        'discount_code' => [
                            "Invalid Discount Code!"
                        ]
                    ],
                    "message" => "Validation Errors Found!"
                ], 422);
            }

            if(is_null($coupon->pm_coupon_id)) {
                return response()->json([
                    "error" => 1,
                    "data" => [
                        'discount_code' => [
                            "Invalid Discount Code!"
                        ]
                    ],
                    "message" => "Validation Errors Found!"
                ], 422);
            }

            if($coupon->expires_at <= now()) {
                return response()->json([
                    "error" => 1,
                    "data" => [
                        'discount_code' => [
                            "Your Discount Code is Expired!"
                        ]
                    ],
                    "message" => "Validation Errors Found!"
                ], 422);
            }

            if($coupon->users()->exists()) {
                if($coupon->users()->count() >= $coupon->max_uses) {
                    return response()->json([
                        "error" => 1,
                        "data" => [
                            'discount_code' => [
                                "Your Discount Code Limit Exceeded!"
                            ]
                        ],
                        "message" => "Validation Errors Found!"
                    ], 422);
                }
            }
        }

        try {
            DB::beginTransaction();

            $stripe_customer = null;
            $stripe_subscription = null;
            $stripe_card = null;
            $payment_token = null;

            $fullname = $package->free_plan ? $request->fullname : $request->card_holder_name;
            $fullname = explode(' ', $fullname);
            $first_name = $fullname[0];
            unset($fullname[0]);
            $last_name = count($fullname) > 0 ? implode(' ', $fullname) : null;
            $password = explode('@', $request->email)[0] . '12345';
            $user = User::create([
                "user_type" => "customer",
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email'  => $request->email,
                "email_verified_at" => now(),
                'password' => bcrypt($request->password),
                'avatar_color' => get_avatar_color(),
                'status' => 'active'
            ]);

            $user->customer_details()->create([
                'is_avail_trial' => 0,
                'is_avail_free_plan' => 0,
                'auto_renewal_subscription' => 1,
                'pm_customer_id' => null,
            ]);

            if(!$package->free_plan) {
                if($request->payment_method == 'stripe') {
                    if($request->paymentMethodId == '') {
                        return response()->json([
                            "error" => 1,
                            "message" => "Token Not Found!"
                        ], 404);
                    }

                    $payment_token = $request->paymentMethodId;
                    if($user->customer_details()->exists() && $user->customer_details->pm_customer_id === null) {
                        $stripe_customer = $this->stripe->customers->create([
                            'name' => $user->fullname,
                            'email' => $user->email,
                            'phone' => $user->phone_number,
                            'payment_method' => $payment_token,
                            'invoice_settings' => [
                                'default_payment_method' => $payment_token
                            ]
                        ]);
                    }

                    if(!$package->duration_lifetime) {

                        $package_pm = $package->payment_methods()->stripe()->first();
                        $ssData = [
                            'customer' => $stripe_customer->id,
                            'items' => [
                                ['plan' => $package_pm->pm_price_id]
                            ]
                        ];

                        if($package->trial_period_days !== null && $user->customer_details->is_avail_trial === 0) {
                            $ssData['trial_period_days'] = (int) $package->trial_period_days;
                        }

                        if($coupon != null) {
                            $ssData['coupon'] = $coupon->pm_coupon_id;
                        }

                        $stripe_subscription = $this->stripe->subscriptions->create($ssData);
                        $stripe_card = $this->stripe->paymentMethods->retrieve($payment_token);
                    } else {

                        if($coupon != null) {
                            $priceWithGST  = discount_price($priceWithGST, $coupon->amount, $coupon->type);
                        }

                        $stripe_subscription = $this->stripe->paymentIntents->create([
                            'customer' => $stripe_customer->id,
                            'amount' => $priceWithGST * 100,
                            'currency' => 'aud',
                            'payment_method_types' => ['card'],
                            'payment_method' => $payment_token,
                            'confirm' => true,
                            'metadata' => [
                                'base_price' => number_format($price, 2),
                                'gst_10_percent' => number_format($gst, 2),
                                'total_with_gst' => number_format($priceWithGST, 2),
                            ],
                        ]);
                    }
                }
            }

            if($request->payment_method == 'stripe') {
                if(!$package->free_plan) {
                    $package_pm = $package->payment_methods()->stripe()->first();

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
                            'amount' => $priceWithGST,
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

                        if($coupon != null) {
                            $coupon->users()->attach($user->id);
                        }

                        $user_data = [];
                        if(!is_null($stripe_customer)) {
                            $user_data['pm_customer_id'] = $stripe_customer->id;
                        }

                        if($package->trial_period_days !== null && $user->customer_details->is_avail_trial === 0) {
                            $user_data['is_avail_trial'] = 1;
                        }

                        $user->customer_details()->update($user_data);

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
                                'amount' => $priceWithGST,
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
                            'pm_id' => $payment_token,
                            'payment_method' => $request->payment_method,
                            'name' => $package->title,
                            'amount' => $priceWithGST,
                            'payment_method' => $payment_token,
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

                        if($coupon != null) {
                            $coupon->users()->attach($user->id);
                        }

                        $user_data = [];
                        if(!is_null($stripe_customer)) {
                            $user_data['pm_customer_id'] = $stripe_customer->id;
                        }

                        if($package->trial_period_days !== null && $user->customer_details->is_avail_trial === 0) {
                            $user_data['is_avail_trial'] = 1;
                        }
                        $user->customer_details()->update($user_data);

                        $invoiceData = [
                            'user_id' => $user->id,
                            'package_id' => $subscription->package->id,
                            'subscription_id' => $subscription->id,
                            'pm_invoice_id' => null,
                            'title' => $subscription->name,
                            'description' => $subscription->name. ' ('. Carbon::createFromTimestamp($stripe_subscription->created)->format('M d Y') .' - Lifetime )',
                            'amount' => $priceWithGST,
                            'status' => 'paid',
                            'date' => Carbon::createFromTimestamp($stripe_subscription->created)->toDateTimeString()
                        ];

                        if($coupon != null) {
                            $invoiceData['coupon_id'] = $coupon->id;
                            $invoiceData['coupon_expire_at'] = null;
                        }
                        $subscription->invoices()->create($invoiceData);
                    }

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

                    $subscription = Subscription::create($ssData);
                    $user->customer_details()->update(['is_avail_free_plan' => 1]);

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
            }

            $user->license()->create([
                "uuid" => Str::uuid(),
                "status" => "deactive"
            ]);

            $websites_ids = [];
            if(count($request->websites)>0) {
                foreach($request->websites as $website) {
                    $newWebsite = Website::create([
                        "user_id" => $user->id,
                        "website_name" => $website["website_name"],
                        "website_url" => $website["website_url"],
                        "status" => "active"
                    ]);

                    $websites_ids[] = $newWebsite->id;
                }
            }

            if($package->website_limit) {
                $websites_ids = array_slice($websites_ids, 0, $package->website_limit);
            }

            $subscription->websites()->attach($websites_ids);
            $subscription->websites()->update([
                'status' => 'active'
            ]);

            if($subscription->status == 'active' || $subscription->status == 'trialing') {
                $user->license()->update([
                    'status' => 'active'
                ]);
            }

            dispatch(new SignupMailJob($user, $request->password));
            dispatch(new LoginMailJob($user));

            if (!$user->first_attempt && $user->status == 'active') {
                dispatch(new WelcomeMailJob($user));
                $user->update(['first_attempt' => 1]);
            }

            $coupon_id = ($coupon != null) ? $coupon->id : null;
            dispatch(new SubscriptionCreatedMailJob($user->id, $subscription->id, $package->id, $coupon_id));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "error" => 1,
                "message" => "Error: ". $e->getMessage()
            ], 400);
        }

        $tokenData = $user->createToken(config('app.name'));
        $token = $tokenData->accessToken;
        $expiration = $tokenData->token->expires_at->diffInSeconds(now());

        return response()->json([
            "error" => 0,
            "data" => [
                'subscription' => $subscription->load('user', 'package', 'coupon', 'websites'),
                'user' => $user,
                'authorisation' => [
                    'type' => 'Bearer',
                    'token' => $token,
                    'expiration' => $expiration
                ]
            ],
            "message" => "Thanks for Subscribing!"
        ], 200);
    }

    public function create_feedback(FeedBackRequest $request)
    {
        $clientIP = $request->ip();
        $clientInfo = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$clientIP));

        $feedback = FeedBack::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'ip_address' => $clientIP,
            'country_code' => $clientInfo->geoplugin_countryCode,
            'country_name' => $clientInfo->geoplugin_countryName,
            'city' => $clientInfo->geoplugin_city,
            'region' => $clientInfo->geoplugin_region,
            'latitude' => $clientInfo->geoplugin_latitude,
            'longitude' => $clientInfo->geoplugin_longitude,
            'timezone' => $clientInfo->geoplugin_timezone,
            'continent_code' => $clientInfo->geoplugin_continentCode,
            'continent_name' => $clientInfo->geoplugin_continentName,
            'currency_code' => $clientInfo->geoplugin_currencyCode
        ]);

        dispatch(new FeedBackMailJob($feedback));

        return response()->json([
            "error" => 0,
            "message" => "Your message has been sent. Thank you!"
        ], 200);
    }
}
