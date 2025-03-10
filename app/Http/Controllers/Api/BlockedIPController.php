<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeadBlockedIP;
use App\Models\Lead;
use App\Models\Website;
use App\Traits\ApiPaginate;
use Illuminate\Http\Request;

class BlockedIPController extends Controller
{

    use ApiPaginate;

    public function get_all(Request $request)
    {
        $user = auth()->user();

        $order = (object)[
            'orderby' => $request->filled('orderby') ? $request->orderby : 'id',
            'order' => $request->filled('order') ? $request->order : 'DESC',
        ];

        $leadsQuery = Lead::with(['user', 'website','lead_blocked_ip'])
            ->byUser($user->id)
            ->filterLeads($request)
            ->orderBy($order->orderby, $order->order);

        if($request->website_id) {
            $leadsQuery->where('website_id', $request->website_id);
        }

        if ($request->filled('perpage')) {
            $leads = $leadsQuery->paginate($request->perpage);
            $filteredLeads = collect($leads->items())->unique(function ($lead) {
                $formData = json_decode($lead->form_data, true);
                return data_get($formData, 'visitor_info.ip');
            })->values();
        } else {
            if ($request->filled('limit')) {
                $leadsQuery->limit($request->limit);
            }
            $leads = $leadsQuery->get();
            $filteredLeads = $leads->unique(function ($lead) {
                $formData = json_decode($lead->form_data, true);
                return data_get($formData, 'visitor_info.ip');
            })->values();
        }


        $response = [
            "error" => 0,
            "data" => $filteredLeads,
            "message" => "Leads have been successfully retrieved"
        ];

        if ($request->filled('perpage')) {
            $response['paginate'] = $this->paginate($leads);
        }

        return response()->json($response, 200);
    }


    public function trackIP(Request $request,$id,$ip){
      $formId = LeadBlockedIP::where('form_id',$id)->where('ip_address',$ip)->where('is_blocked',1)->first();
      if(!$formId){
          if($request->domain){
              $website = Website::where('website_url',$request->domain)->first();
              if($website){
                  $formId = LeadBlockedIP::where('website_id',$website->id)->where('ip_address',$ip)->where('is_blocked',1)->first();
              }else{
                  return response()->json(["error"=>"not found"],404);
              }

          }
      }
     return response()->json(["data"=>$formId],200);
    }


    public function blockedIP($id){

        $lead = Lead::find($id);
        if(empty($lead)){
            $response = [
                "error" => 1,
                "message" => "Lead not found"
            ];
        }
        $formData = json_decode($lead->form_data, true);
        $input = [
            "ip_address" => $formData['visitor_info']['ip'],
            "is_blocked" => 1,
            "lead_id" => $id,
            "blocked_by" => auth()->id(),
            "website_id" => $lead->website_id,
            "form_id" => $lead->wpform_id,
        ];
        $lead = LeadBlockedIP::updateOrCreate(['ip_address'=>$formData['visitor_info']['ip']],$input);

        $response = [
            "error" => 0,
            "data" => $lead,
            "message" => "IP have been successfully blocked"
        ];
        return response()->json($response, 200);
    }


    public function UnBlocked($id){
        $lead = LeadBlockedIP::find($id);
        if(empty($lead)){
            $response = [
                "error" => 1,
                "message" => "Lead not found"
            ];
        }

        $lead->update(['is_blocked' => 0]);

        $response = [
            "error" => 0,
            "data" => $lead,
            "message" => "IP have been successfully Unblocked"
        ];
        return response()->json($response, 200);
    }

}
