<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiPaginate;
use App\Models\{
    Coupon,
    User
};
use App\Http\Requests\{
    CouponStoreRequest,
    CouponUpdateRequest,
    CouponBulkDeleteRequest,
    CouponStatusUpdateRequest
};
use Stripe\StripeClient;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    use ApiPaginate;

    protected $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function resolveUser(Request $request)
    {
        if ($request->filled('user_id')) {
            return User::whereId($request->user_id)->first();
        } else {
            return $request->user();
        }
    }

    public function get_count(Request $request)
    {
        $user = $this->resolveUser($request);
        if (is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $count = Coupon::filterCoupons($request)->count();
        return response()->json([
            "error" => 0,
            "data" => [
                "count" => $count
            ],
            "message" => "Coupon count have been successfully retrieved"
        ], 200);
    }

    public function get_all(Request $request)
    {
        $user = $this->resolveUser($request);
        if (is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $order = (object) [
            'orderby' => $request->filled('orderby') ? $request->orderby : 'id',
            'order' => $request->filled('order') ? $request->order : 'DESC',
        ];

        $couponQuery = Coupon::filterCoupons($request)->orderBy($order->orderby, $order->order);

        if ($request->filled('perpage')) {
            $coupons = $couponQuery->paginate($request->perpage);
        } else {
            if ($request->filled('limit')) {
                $couponQuery->limit($request->limit);
            }

            $coupons = $couponQuery->get();
        }

        $response = [
            "error" => 0,
            "data" => $request->filled('perpage') ? $coupons->items() : $coupons,
            "message" => "Coupons have been successfully retrieved"
        ];

        if ($request->filled('perpage')) {
            $response['paginate'] = $this->paginate($coupons);
        }
        return response()->json($response, 200);
    }

    public function get_by(Request $request, $id)
    {
        $user = $this->resolveUser($request);
        if (is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $coupon = Coupon::whereId($id)->first();
        if (is_null($coupon)) {
            return response()->json([
                "error" => 1,
                "message" => "Coupon Not Found!"
            ], 404);
        }

        return response()->json([
            "error" => 0,
            "data" => $coupon,
            "message" => "Coupon have been successfully retrieved"
        ], 200);
    }

    public function store(CouponStoreRequest $request)
    {
        $user = $this->resolveUser($request);
        if (is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        try {
            DB::beginTransaction();

            $coupon = Coupon::create([
                'title' => $request->title,
                'code' => $request->code,
                'description' => $request->description,
                'type' => $request->type,
                'amount' => $request->amount,
                'max_uses' => $request->max_uses,
                'max_uses_user' => $request->max_uses_user,
                'duration' => $request->duration,
                'duration_month' => $request->duration_month,
                'expires_at' => $request->expires_at,
                'status' => 'active'
            ]);

            $stripe_coupon_data = [
                'name' => $request->title
            ];

            if ($request->type == 'fixed') {
                $stripe_coupon_data['amount_off'] = $request->amount;
                $stripe_coupon_data['currency'] = 'AUD';
            } else {
                $stripe_coupon_data['percent_off'] = $request->amount;
            }

            if ($request->duration == 'repeating') {
                $stripe_coupon_data['duration'] = $request->duration;
                $stripe_coupon_data['duration_in_months'] = $request->duration_month;
            } else {
                $stripe_coupon_data['duration'] = $request->duration;
            }

            $stripe_coupon = $this->stripe->coupons->create($stripe_coupon_data);

            $coupon->update([
                'pm_coupon_id' => $stripe_coupon->id
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "error" => 1,
                "message" => "Error: " . $e->getMessage()
            ], 400);
        }

        return response()->json([
            "error" => 0,
            "data" => $coupon,
            "message" => "Coupon has been successfully created"
        ], 200);
    }

    public function update(CouponUpdateRequest $request, $id)
    {
        $user = $this->resolveUser($request);
        if (is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $coupon = Coupon::whereId($id)->first();
        if (is_null($coupon)) {
            return response()->json([
                "error" => 1,
                "message" => "Coupon Not Found!"
            ], 404);
        }

        $coupon->update([
            'title' => $request->title,
            'description' => $request->description,
            'max_uses' => $request->max_uses,
            'max_uses_user' => $request->max_uses_user,
            'expires_at' => $request->expires_at
        ]);

        return response()->json([
            "error" => 0,
            "data" => $coupon,
            "message" => "Coupon has been successfully updated"
        ], 200);
    }

    public function status(CouponStatusUpdateRequest $request, $id)
    {
        $user = $this->resolveUser($request);
        if (is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $coupon = Coupon::whereId($id)->first();
        if (is_null($coupon)) {
            return response()->json([
                "error" => 1,
                "message" => "Coupon Not Found!"
            ], 404);
        }

        $coupon->update([
            'status' => $request->status
        ]);

        return response()->json([
            "error" => 0,
            "data" => $coupon,
            "message" => "Coupon status has been successfully updated"
        ], 200);
    }

    public function delete(Request $request, $id)
    {
        $user = $this->resolveUser($request);
        if (is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $coupon = Coupon::whereId($id)->first();
        if (is_null($coupon)) {
            return response()->json([
                "error" => 1,
                "message" => "Coupon Not Found!"
            ], 404);
        }

        if ($coupon->users()->exists()) {
            return response()->json([
                "error" => 1,
                "message" => "You can't delete this coupon."
            ], 404);
        }

        try {
            DB::beginTransaction();

            $this->stripe->coupons->delete($coupon->pm_coupon_id, []);
            $coupon->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "error" => 1,
                "message" => "Error: " . $e->getMessage()
            ], 400);
        }

        return response()->json([
            "error" => 0,
            "message" => "Coupon has been successfully deleted"
        ], 200);
    }

    public function bulk_delete(CouponBulkDeleteRequest $request)
    {
        $user = $this->resolveUser($request);
        if (is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $coupons = Coupon::whereIn('id', $request->ids)->get();
        if (!$coupons->count()) {
            return response()->json([
                "error" => 1,
                "message" => "Coupons Not Found!"
            ], 404);
        }

        $deletedCount = 0;
        $failedToDelete = [];

        foreach ($coupons as $coupon) {
            try {
                DB::beginTransaction();

                if (!$coupon->users()->exists()) {
                    $this->stripe->coupons->delete($coupon->pm_coupon_id, []);
                    $coupon->delete();

                    $deletedCount++;
                } else {
                    $failedToDelete[] = $coupon->id;
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $failedToDelete[] = $coupon->id;
            }
        }

        $message = '';
        if ($deletedCount > 0) {
            $message .= $deletedCount . ' Coupons deleted successfully.';
        }

        if (count($failedToDelete) > 0) {
            $message .= ' Failed to delete ' . count($failedToDelete) . ' coupons.';
        }

        return response()->json([
            'error' => 0,
            'data' => [
                'failed_items' => $failedToDelete
            ],
            'message' => $message
        ], 200);
    }

    public function validateCoupon(Request $request)
    {
        $code = $request->code;
        $package_id = $request->package_id;

        $coupon = Coupon::where('code', $code)->where('status', 'active')->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired coupon.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'discount' => $coupon->amount,
            'discount_type' => $coupon->type,
            'title' => $coupon->title,
            'code' => $coupon->code,
            'message' => 'Coupon applied successfully.'
        ]);
    }
}
