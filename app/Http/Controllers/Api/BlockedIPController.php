<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class BlockedIPController extends Controller
{
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

        $groupedLeads = $leads->map(function ($lead) {
            $formData = json_decode($lead->form_data, true);
            $ip = $formData['visitor_info']['ip'] ?? 'Unknown';
            return [
                'ip' => $ip,
                'lead' => $lead
            ];
        })->groupBy('ip');

        $response = [
            "error" => 0,
            "data" => $request->filled('perpage') ? $groupedLeads : $groupedLeads,
            "message" => "Leads have been successfully retrieved"
        ];

        if($request->filled('perpage')) {
            $response['paginate'] = $this->paginate($leads);
        }
        return response()->json($response, 200);
    }
}
