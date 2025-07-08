<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }


    public function resolveUser(Request $request) {
        if($request->filled('user_id')) {
            return User::whereId($request->user_id)->first();
        } else {
            return $request->user();
        }
    }

    public function get_data(Request $request) {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $customers = User::customer()->filterUsers($request)->get();
        $total_customers = $customers->count();
        $total_active_customers = $customers->where('status', 'active')->count();
        $total_deactive_customers = $customers->where('status', 'deactive')->count();

        $subscriptions = Subscription::filterSubscriptions($request)->get();
        $total_subscriptions = $subscriptions->count();
        $total_active_subscriptions = $subscriptions->where('status', 'active')->count();
        $total_trialing_subscriptions = $subscriptions->where('status', 'trialing')->count();
        $total_canceled_subscriptions = $subscriptions->where('status', 'canceled')->count();

        return response()->json([
            'error' => 0,
            "data" => [
                'customers' => [
                    'total_customers' => $total_customers,
                    'total_active_customers' => $total_active_customers,
                    'total_deactive_customers' => $total_deactive_customers,
                ],
                'subscriptions' => [
                    'total_subscriptions' => $total_subscriptions,
                    'total_active_subscriptions' => $total_active_subscriptions,
                    'total_trialing_subscriptions' => $total_trialing_subscriptions,
                    'total_canceled_subscriptions' => $total_canceled_subscriptions,
                ],
            ],
            "message" => "Success",
        ], 200);
    }
}
