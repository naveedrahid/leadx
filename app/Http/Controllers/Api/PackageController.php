<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiPaginate;
use Illuminate\Support\Facades\Log;
use App\Models\{
    Package,
    User
};
use App\Http\Requests\{
    PackageStoreRequest,
    PackageUpdateRequest,
    PackageBulkDeleteRequest,
    PackageStatusUpdateRequest
};
use Stripe\StripeClient;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    use ApiPaginate;

    protected $stripe;

    public function __construct() {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function updateStripeProductPrices()
    {
        $products = $this->stripe->products->all([
            'active' => true,
            'limit' => 100
        ]);

        foreach ($products->data as $product) {
            $prices = $this->stripe->prices->all([
                'product' => $product->id,
                'limit' => 1,
                'active' => true
            ]);

            if (count($prices->data)) {
                $oldPrice = $prices->data[0];
                $oldAmount = $oldPrice->unit_amount / 100;

                $package = Package::whereHas('payment_methods', function ($query) use ($product) {
                    $query->where('pm_product_id', $product->id);
                })->first();

                if ($package) {
                    $newAmount = round($package->regular_price * 1.10, 2);

                    $priceData = [
                        'unit_amount' => $newAmount * 100,
                        'currency' => 'aud',
                        'product' => $product->id,
                        'nickname' => "GST Included Price - {$newAmount} AUD",
                        'recurring' => [
                            'interval' => $package->duration_type,
                            'interval_count' => $package->duration
                        ]
                    ];

                    $newPrice = $this->stripe->prices->create($priceData);
                    $subscriptions = $this->stripe->subscriptions->all([
                        'limit' => 100,
                        'status' => 'active'
                    ]);

                    foreach ($subscriptions->data as $subscription) {
                        foreach ($subscription->items->data as $item) {
                            if ($item->price->id === $oldPrice->id) {
                                $this->stripe->subscriptionItems->update(
                                    $item->id,
                                    ['price' => $newPrice->id]
                                );
                                Log::info('Subscription {$subscription->id} price updated');
                            }
                        }
                    }

                    $this->stripe->prices->update($oldPrice->id, ['active' => false]);
                    $package->update([
                        'regular_price' => $newAmount
                    ]);

                    $package->payment_methods()->where('payment_method', 'stripe')->update([
                        'pm_price_id' => $newPrice->id
                    ]);
                }
            }
        }

        return true;
    }

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

        $count = Package::filterPackages($request)->count();
        return response()->json([
            "error" => 0,
            "data" => [
                "count" => $count
            ],
            "message" => "Package count have been successfully retrieved"
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

        $packageQuery = Package::filterPackages($request)->orderBy($order->orderby, $order->order);

        if ($request->filled('perpage')) {
            $packages = $packageQuery->paginate($request->perpage);
        } else {
            if($request->filled('limit')) {
                $packageQuery->limit($request->limit);
            }

            $packages = $packageQuery->get();
        }

        $packages->load('payment_methods');

        $response = [
            "error" => 0,
            "data" => $request->filled('perpage') ? $packages->items() : $packages,
            "message" => "Packages have been successfully retrieved"
        ];

        if($request->filled('perpage')) {
            $response['paginate'] = $this->paginate($packages);
        }
        return response()->json($response, 200);
    }

    public function get_by(Request $request, $id)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $package = Package::whereId($id)->first();
        if (is_null($package)) {
            return response()->json([
                "error" => 1,
                "message" => "Package Not Found!"
            ], 404);
        }

        $package->load('payment_methods');

        return response()->json([
            "error" => 0,
            "data" => $package,
            "message" => "Package have been successfully retrieved"
        ], 200);
    }

    public function store(PackageStoreRequest $request)
    {
        // dd($request->toArray());
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        try {
            DB::beginTransaction();

            if($request->recommended == 1) {
                Package::where('recommended', true)->update([
                    'recommended' => false
                ]);
            }

            $regularPrice = $request->regular_price;
            $stripPrecent = $request->strip_precent;
            $isChecked = $request->is_checked;

            if ($isChecked && $stripPrecent > 0) {
                $regularPrice = $regularPrice + ($regularPrice * $stripPrecent / 100);
            }

            $package = Package::create([
                'title' => $request->title,
                'duration' => $request->duration,
                'duration_type' => $request->duration_type,
                'duration_lifetime' => $request->duration_lifetime,
                'trial_period_days' => $request->trial_period_days,
                'regular_price' => $regularPrice,
                'strip_precent' => $stripPrecent,
                'is_checked' => $isChecked,
                'sale_price' => $request->sale_price,
                'features' => count($request->features) ? json_encode($request->features) : '',
                'description' => $request->description,
                'recommended' => $request->recommended,
                'sort' => $request->sort ? $request->sort : 0,
                'website_limit' => $request->website_limit,
                'lead_limit' => $request->lead_limit,
                'app_access' => $request->app_access,
                'is_private' => $request->is_private,
                'status' => 'active'
            ]);

            if(!$request->free_package && !$package->duration_lifetime) {
                $product = $this->stripe->products->create([
                    'name' => $package->title,
                    'active' => true
                ]);

                $price = $this->stripe->prices->create([
                    'unit_amount' => $regularPrice * 100,
                    'currency' => currency_code(),
                    'product' => $product->id,
                    'recurring' => [
                        'interval' => $package->duration_type,
                        'interval_count' => $package->duration
                    ]
                ]);

                $package->payment_methods()->create([
                    'payment_method' => 'stripe',
                    'pm_product_id' => $product->id,
                    'pm_price_id' => $price->id
                ]);

                $package->load('payment_methods');
            }

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
            "data" => $package,
            "message" => "Package has been successfully created"
        ], 200);
    }

    public function update(PackageUpdateRequest $request, $id)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $package = Package::whereId($id)->first();
        if (is_null($package)) {
            return response()->json([
                "error" => 1,
                "message" => "Package Not Found!"
            ], 404);
        }

        try {
            DB::beginTransaction();


            $regularPrice = $package->regular_price;
            $oldPercent = $package->strip_precent ?? 0;
            $isChecked = $request->is_checked;

            if (!$isChecked && $oldPercent > 0) {
                $regularPrice = $regularPrice / (1 + ($oldPercent / 100));
                $regularPrice = round($regularPrice, 2);
            }


            $package->update([
                'title' => $request->title,
                'features' => count($request->features) ? json_encode($request->features) : '',
                'description' => $request->description,
                'recommended' => $request->recommended,
                'sort' => $request->sort ? $request->sort : 0,
                'website_limit' => $request->website_limit,
                'lead_limit' => $request->lead_limit,
                'app_access' => $request->app_access,
                'regular_price' => $regularPrice,
                'strip_precent' => $request->strip_precent,
                'is_checked' => $isChecked,
                'status' => $request->status
            ]);

            $pm = $package->payment_methods()->where('payment_method', 'stripe')->first();
            if(!is_null($pm)) {
                $this->stripe->products->update($pm->pm_product_id, [
                    'name' => $package->title
                ]);

                $this->stripe->prices->update($pm->pm_price_id, [
                    'active' => false
                ]);

                $newPrice = $this->stripe->prices->create([
                    'unit_amount' => $regularPrice * 100,
                    'currency' => currency_code(),
                    'product' => $pm->pm_product_id,
                    'recurring' => [
                        'interval' => $package->duration_type,
                        'interval_count' => $package->duration
                    ]
                ]);

                $pm->pm_price_id = $newPrice->id;
                $pm->save();

            }

            $package->load('payment_methods');

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
            "data" => $package,
            "message" => "Package has been successfully updated"
        ], 200);
    }

    public function status(PackageStatusUpdateRequest $request, $id)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $package = Package::whereId($id)->first();
        if (is_null($package)) {
            return response()->json([
                "error" => 1,
                "message" => "Package Not Found!"
            ], 404);
        }

        if($package->subscriptions()->exists()) {
            return response()->json([
                "error" => 1,
                "message" => "Unable to update package status."
            ], 404);
        }

        try {
            DB::beginTransaction();

            $package->update([
                'status' => $request->status
            ]);

            $pm = $package->payment_methods()->where('payment_method', 'stripe')->first();
            if(!is_null($pm)) {
                $this->stripe->products->update($package->pm_product_id, [
                    'active' => ($request->status == 'active') ? true : false
                ]);
            }

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
            "data" => $package,
            "message" => "Package status has been successfully updated"
        ], 200);
    }

    public function delete(Request $request, $id)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $package = Package::whereId($id)->first();
        if (is_null($package)) {
            return response()->json([
                "error" => 1,
                "message" => "Package Not Found!"
            ], 404);
        }

        if($package->subscriptions()->exists()) {
            return response()->json([
                "error" => 1,
                "message" => "You can't delete this package."
            ], 404);
        }

        try {
            DB::beginTransaction();

            $pm = $package->payment_methods()->where('payment_method', 'stripe')->first();
            if(!is_null($pm)) {
                $this->stripe->products->update($pm->pm_product_id, [
                    'active' => false
                ]);
            }

            $package->payment_methods()->delete();
            $package->delete();

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
            "data" => null,
            "message" => "Package has been successfully deleted"
        ], 200);
    }

    public function bulk_delete(PackageBulkDeleteRequest $request)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $packages = Package::whereIn('id', $request->ids)->get();
        if (!$packages->count()) {
            return response()->json([
                "error" => 1,
                "message" => "Packages Not Found!"
            ], 404);
        }

        $deletedCount = 0;
        $failedToDelete = [];

        foreach($packages as $package) {
            try {
                DB::beginTransaction();

                if(!$package->subscriptions()->exists()) {
                    $pm = $package->payment_methods()->where('payment_method', 'stripe')->first();
                    if(!is_null($pm)) {
                        $this->stripe->products->update($pm->pm_product_id, [
                            'active' => false
                        ]);
                    }

                    $package->payment_methods()->delete();
                    $package->delete();
                    $deletedCount++;
                } else {
                    $failedToDelete[] = $package->id;
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $failedToDelete[] = $package->id;
            }
        }

        $message = '';
        if ($deletedCount > 0) {
            $message .= $deletedCount . ' Packages deleted successfully.';
        }

        if (count($failedToDelete) > 0) {
            $message .= ' Failed to delete ' . count($failedToDelete) . ' packages.';
        }

        return response()->json([
            'error' => 0,
            'data' => [
                'failed_items' => $failedToDelete
            ],
            'message' => $message
        ], 200);
    }
}
