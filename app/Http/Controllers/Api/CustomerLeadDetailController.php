<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use App\Traits\ApiPaginate;
use Illuminate\Http\Request;

class CustomerLeadDetailController extends Controller
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

        $count = Lead::byUser($user->id)->filterLeads($request)->count();
        return response()->json([
            "error" => 0,
            "data" => [
                "count" => $count
            ],
            "message" => "Lead count have been successfully retrieved"
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

        $leadsQuery = Lead::with(['user', 'website'])->byUser($user->id)->filterLeads($request)->orderBy($order->orderby, $order->order);

        if ($request->filled('perpage')) {
            $leads = $leadsQuery->paginate($request->perpage);
        } else {
            if($request->filled('limit')) {
                $leadsQuery->limit($request->limit);
            }

            $leads = $leadsQuery->get();
        }

        $response = [
            "error" => 0,
            "data" => $request->filled('perpage') ? $leads->items() : $leads,
            "message" => "Leads have been successfully retrieved"
        ];

        if($request->filled('perpage')) {
            $response['paginate'] = $this->paginate($leads);
        }
        return response()->json($response, 200);
    }
}
