<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiPaginate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Http\Requests\{
    CustomerStoreRequest,
    CustomerUpdateRequest,
    CustomerStatusRequest,
    CustomerBulkDeleteRequest
};
use App\Jobs\{
    CustomerCreatedByAdminMailJob,
    CustomerUpdatedByAdminMailJob,
    CustomerStatusUpdatedByAdminMailJob,
    CustomerDeletedByAdminMailJob
};
use Stripe\StripeClient;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    use ApiPaginate;

    protected $stripe;

    public function __construct() {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
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

        $count = User::customer()->filterUsers($request)->count();
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

        $customerQuery = User::customer()->filterUsers($request)->orderBy($order->orderby, $order->order);

        if ($request->filled('perpage')) {
            $customers = $customerQuery->paginate($request->perpage);
        } else {
            if($request->filled('limit')) {
                $customerQuery->limit($request->limit);
            }

            $customers = $customerQuery->get();
        }

        $customers->load('customer_details');

        $response = [
            "error" => 0,
            "data" => $request->filled('perpage') ? $customers->items() : $customers,
            "message" => "Customers have been successfully retrieved"
        ];

        if($request->filled('perpage')) {
            $response['paginate'] = $this->paginate($customers);
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

        $customer = User::customer()->whereId($id)->first();
        if (is_null($customer)) {
            return response()->json([
                "error" => 1,
                "message" => "Customer Not Found!"
            ], 404);
        }

        $customer->load('customer_details');

        return response()->json([
            "error" => 0,
            "data" => $customer,
            "message" => "Customer have been successfully retrieved"
        ], 200);
    }

    public function store(CustomerStoreRequest $request)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        try {
            DB::beginTransaction();

            $filename = null;
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $filename = time().uniqid().'.'.$file->getClientOriginalExtension();
                $file->storeAs('public/users', $filename);
            }

            $customer = User::create([
                "user_type" => "customer",
                "first_name" => $request->first_name,
                "last_name" => $request->last_name,
                "email" => $request->email,
                "phone_number" => $request->phone_number,
                "password" => bcrypt($request->password),
                "profile_image" => $filename,
                "avatar_color" => get_avatar_color(),
                "status" => "active"
            ]);

            $user->customer_details()->create([
                'is_avail_trial' => 0,
                'is_avail_free_plan' => 0,
                'auto_renewal_subscription' => 1,
                'pm_customer_id' => null,
            ]);

            $customer->license()->create([
                'uuid' => Str::uuid(),
                'status' => 'deactive'
            ]);

            dispatch(new CustomerCreatedByAdminMailJob($customer, $request->password));

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
            "data" => $customer,
            "message" => "Customer has been successfully created"
        ], 200);
    }

    public function update(CustomerUpdateRequest $request, $id)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $customer = User::customer()->whereId($id)->first();
        if (is_null($customer)) {
            return response()->json([
                "error" => 1,
                "message" => "Customer Not Found!"
            ], 404);
        }

        try {
            DB::beginTransaction();

            $profile_image = $user->profile_image;
            if($request->has('remove_profile_image')) {
                if (Storage::exists('public/users/' . $user->profile_image)) {
                    Storage::delete('public/users/' . $user->profile_image);
                }

                $profile_image = null;
            }

            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $profile_image = time().uniqid().'.'.$file->getClientOriginalExtension();
                $file->storeAs('public/users', $profile_image);
                
                if (Storage::exists('public/users/' . $user->profile_image)) {
                    Storage::delete('public/users/' . $user->profile_image);
                }
            }

            $status = $customer->status;

            $customer->update([
                "first_name" => $request->first_name,
                "last_name" => $request->last_name,
                "email" => $request->email,
                "phone_number" => $request->phone_number,
                "profile_image" => $profile_image,
                "status" => "active"
            ]);

            if($customer->stripe_id != null) {
                $stripe_customer = $this->stripe->customers->update($customer->stripe_id, [
                    'name' => $customer->fullname,
                    'email' => $customer->email,
                    'phone' => $customer->phone_number
                ]);
            }

            dispatch(new CustomerUpdatedByAdminMailJob($customer));

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
            "data" => $customer,
            "message" => "Customer has been successfully updated"
        ], 200);
    }

    public function status(CustomerStatusRequest $request, $id)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $customer = User::customer()->whereId($id)->first();
        if (is_null($customer)) {
            return response()->json([
                "error" => 1,
                "message" => "Customer Not Found!"
            ], 404);
        }

        $subscription = $customer->subscriptions()->orderby('id', 'desc')->status(['active', 'trialing'])->first();
        if (!is_null($subscription)) {
            return response()->json([
                "error" => 1,
                "message" => "Unable to update customer status."
            ], 404);
        }

        try {
            DB::beginTransaction();

            $customer->update([
                "status" => $request->status
            ]);

            dispatch(new CustomerStatusUpdatedByAdminMailJob($customer));

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
            "data" => $customer,
            "message" => "Customer status has been successfully updated"
        ], 200);
    }

    public function delete(Request $request, $id)
    {
        $user = $this->resolveUser($request);
        if(is_null($user) || $user->user_type != 'admin') {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $customer = User::customer()->whereId($id)->first();
        if (is_null($customer)) {
            return response()->json([
                "error" => 1,
                "message" => "Customer Not Found!"
            ], 404);
        }

        $subscription = $customer->subscriptions()->orderby('id', 'desc')->status(['active', 'trialing'])->first();
        if (!is_null($subscription)) {
            return response()->json([
                "error" => 1,
                "message" => "Unable to delete customer."
            ], 404);
        }

        $pm_customer_id = $customer->customer_details?->pm_customer_id;

        try {
            DB::beginTransaction();

            if($customer->subscriptions()->count()) {
                foreach ($customer->subscriptions as $subscription) {
                    $subscription->websites()->detach();
                }
                $customer->subscriptions()->delete();
            }

            if($customer->subscription_invoices()->count()) {
                $customer->subscription_invoices()->delete();
            }

            if($customer->coupons()->count()) {
                $customer->coupons()->detach();
            }

            if($customer->payment_cards()->count()) {
                $customer->payment_cards()->delete();
            }

            if($customer->websites()->count()) {
                $customer->websites()->delete();
            }

            if($customer->payment_links()->count()) {
                $customer->payment_links()->delete();
            }

            if($customer->customer_details()->exists()) {
                $customer->customer_details()->delete();
            }

            if($customer->license()->exists()) {
                $customer->license()->delete();
            }

            if($customer->leads()->count()) {
                foreach($customer->leads as $lead) {
                    $form_data = json_decode($lead->form_data);
                    if(!empty($form_data) && isset($form_data->data->file)) {
                        foreach($form_data->data->file as $key => $value) {
                            if (Storage::exists('/public/leads/' . $value->name)) {
                                Storage::delete('/public/leads/' . $value->name);
                            }
                        }
                    }
                }

                $customer->leads()->delete();
            }

            if ($customer->profile_image) {
                if (Storage::exists('/public/users/' . $customer->profile_image)) {
                    Storage::delete('/public/users/' . $customer->profile_image);
                }
            }

            dispatch(new CustomerDeletedByAdminMailJob(
                $customer->fullname, 
                $customer->email,
                now()->format('M d Y'),
                now()->format('h:i:s A')
            ));

            $customer->delete();

            if($pm_customer_id != null) {
                $this->stripe->customers->delete($pm_customer_id);
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
            "message" => "Customer has been successfully deleted"
        ], 200);
    }

    public function bulk_delete(CustomerBulkDeleteRequest $request)
    {
        $user = $this->resolveUser($request);
        if(is_null($user) || $user->user_type != 'admin') {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $customers = User::customer()->whereIn('id', $request->ids)->get();
        if (!$customers->count()) {
            return response()->json([
                "error" => 1,
                "message" => "Customers Not Found!"
            ], 404);
        }

        $deletedCount = 0;
        $failedToDelete = [];

        foreach($customers as $customer) {
            try {
                DB::beginTransaction();

                $subscription = $customer->subscriptions()->orderby('id', 'desc')->status(['active', 'trialing'])->first();
                if (is_null($subscription)) {
                    $pm_customer_id = $customer->customer_details?->pm_customer_id;
                    
                    if($customer->subscriptions()->count()) {
                        foreach ($customer->subscriptions as $subscription) {
                            $subscription->websites()->detach();
                        }
                        $customer->subscriptions()->delete();
                    }
        
                    if($customer->subscription_invoices()->count()) {
                        $customer->subscription_invoices()->delete();
                    }
        
                    if($customer->coupons()->count()) {
                        $customer->coupons()->detach();
                    }
        
                    if($customer->payment_cards()->count()) {
                        $customer->payment_cards()->delete();
                    }
        
                    if($customer->websites()->count()) {
                        $customer->websites()->detach();
                    }
        
                    if($customer->payment_links()->count()) {
                        $customer->payment_links()->delete();
                    }
        
                    if($customer->customer_details()->exists()) {
                        $customer->customer_details()->delete();
                    }
        
                    if($customer->license()->exists()) {
                        $customer->license()->delete();
                    }
        
                    if($customer->leads()->count()) {
                        foreach($customer->leads as $lead) {
                            $form_data = json_decode($lead->form_data);
                            if(!empty($form_data) && isset($form_data->data->file)) {
                                foreach($form_data->data->file as $key => $value) {
                                    if (Storage::exists('/public/leads/' . $value->name)) {
                                        Storage::delete('/public/leads/' . $value->name);
                                    }
                                }
                            }
                        }
        
                        $customer->leads()->delete();
                    }
        
                    if ($customer->profile_image) {
                        if (Storage::exists('/public/users/' . $customer->profile_image)) {
                            Storage::delete('/public/users/' . $customer->profile_image);
                        }
                    }
        
                    dispatch(new CustomerDeletedByAdminMailJob(
                        $customer->fullname, 
                        $customer->email,
                        now()->format('M d Y'),
                        now()->format('h:i:s A')
                    ));
        
                    $customer->delete();
        
                    if($pm_customer_id != null) {
                        $this->stripe->customers->delete($pm_customer_id);
                    }

                    $deletedCount++;
                } else {
                    $failedToDelete[] = $customer->id;
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $failedToDelete[] = $customer->id;
            }
        }

        $message = '';
        if ($deletedCount > 0) {
            $message .= $deletedCount . ' Customers deleted successfully.';
        }

        if (count($failedToDelete) > 0) {
            $message .= ' Failed to delete ' . count($failedToDelete) . ' customers.';
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
