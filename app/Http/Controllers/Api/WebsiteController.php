<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiPaginate;
use App\Models\{
    Website,
    User
};
use App\Http\Resources\WebsiteResource;
use App\Http\Requests\{
    WebsiteStoreRequest,
    WebsiteUpdateRequest,
    WebsiteStatusRequest,
    WebsiteBulkDeleteRequest
};
use Illuminate\Support\Facades\DB;

class WebsiteController extends Controller
{
    use ApiPaginate;

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

        $count = Website::filterWebsites($request)->count();
        return response()->json([
            "error" => 0,
            "data" => [
                "count" => $count
            ],
            "message" => "Website count have been successfully retrieved"
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

        $websiteQuery = Website::byUser($user->id)->filterWebsites($request)->orderBy($order->orderby, $order->order);

        if ($request->filled('perpage')) {
            $websites = $websiteQuery->paginate($request->perpage);
        } else {
            if($request->filled('limit')) {
                $websiteQuery->limit($request->limit);
            }

            $websites = $websiteQuery->get();
        }

        $response = [
            "error" => 0,
            "data" => $request->filled('perpage') ? $websites->items() : $websites,
            "message" => "Websites have been successfully retrieved"
        ];

        if($request->filled('perpage')) {
            $response['paginate'] = $this->paginate($websites);
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

        $website = Website::byUser($user->id)->whereId($id)->first();
        if (is_null($website)) {
            return response()->json([
                "error" => 1,
                "message" => "Website Not Found!"
            ], 404);
        }

        return response()->json([
            "error" => 0,
            "data" => $website,
            "message" => "Website have been successfully retrieved"
        ], 200);
    }

    public function store(WebsiteStoreRequest $request)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $website = Website::create([
            'user_id' => $user->id,
            'website_name' => $request->website_name,
            'website_url' => rtrim($request->website_url, '/'),
            'status' => 'active'
         ]);
 
         return response()->json([
             "error" => 0,
             "data" => $website,
             "message" => "Website has been successfully created"
         ], 200);
    }

    public function update(WebsiteUpdateRequest $request, $id)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $website = Website::byUser($user->id)->whereId($id)->first();
        if (is_null($website)) {
            return response()->json([
                "error" => 1,
                "message" => "Website Not Found!"
            ], 404);
        }

        $website->update([
            'website_name' => $request->website_name,
            'website_url' => rtrim($request->website_url, '/'),
            'status' => 'active'
         ]);
 
         return response()->json([
             "error" => 0,
             "data" => $website,
             "message" => "Website has been successfully updated"
         ], 200);
    }

    public function status(WebsiteStatusRequest $request, $id)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $website = Website::byUser($user->id)->whereId($id)->first();
        if (is_null($website)) {
            return response()->json([
                "error" => 1,
                "message" => "Website Not Found!"
            ], 404);
        }

        $website->update([
            'status' => $request->status
        ]);

        return response()->json([
            "error" => 0,
            "data" => $website,
            "message" => "Website status has been successfully updated"
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

        $website = Website::byUser($user->id)->whereId($id)->first();
        if (is_null($website)) {
            return response()->json([
                "error" => 1,
                "message" => "Website Not Found!"
            ], 404);
        }

        if($website->leads()->exists()) {
            return response()->json([
                "error" => 1,
                "message" => "You can't delete this website."
            ], 404);
        }
        
        $website->subscriptions()->detach();
        $website->delete();

        return response()->json([
            "error" => 0,
            "data" => null,
            "message" => "Website has been successfully deleted"
        ], 200);
    }

    public function bulk_delete(WebsiteBulkDeleteRequest $request)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $websites = Website::byUser($user->id)->whereIn('id', $request->ids)->get();
        if (!$websites->count()) {
            return response()->json([
                "error" => 1,
                "message" => "Websites Not Found!"
            ], 404);
        }

        $deletedCount = 0;
        $failedToDelete = [];

        foreach($websites as $website) {
            try {
                DB::beginTransaction();

                if(!$website->leads()->exists()) {
                    $website->subscriptions()->detach();
                    $website->delete();
                    $deletedCount++;
                } else {
                    $failedToDelete[] = $website->id;
                }
                
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $failedToDelete[] = $website->id;
            }
        }

        $message = '';
        if ($deletedCount > 0) {
            $message .= $deletedCount . ' Websites deleted successfully.';
        }

        if (count($failedToDelete) > 0) {
            $message .= ' Failed to delete ' . count($failedToDelete) . ' websites.';
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
