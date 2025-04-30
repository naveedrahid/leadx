<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiPaginate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\{
    LeadStatusRequest,
    LeadBulkDeleteRequest
};
use App\Models\{
    Lead,
    Website,
    User
};
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LeadsExport;
use App\Exports\LeadsExport2;
use Maatwebsite\Excel\Excel as MWExcel;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class CustomerLeadController extends Controller
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

    public function get_by(Request $request, $id)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $lead = Lead::byUser($user->id)->whereId($id)->first();
        if (is_null($lead)) {
            return response()->json([
                "error" => 1,
                "message" => "Lead Not Found!"
            ], 404);
        }

        return response()->json([
            "error" => 0,
            "data" => $lead,
            "message" => "Lead have been successfully retrieved"
        ], 200);
    }

    public function website_forms(Request $request){
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }
        $leads = Lead::byUser($user->id)->where('website_id',$request->website_id)->get();
        $forms = [];
        $usedForms = [];
        if($leads->count()) {
            foreach($leads as $lead) {
                if ( !in_array($lead->wpform_id, $usedForms) ) {
                    $usedForms[] = $lead->wpform_id;
                    $forms[] = [
                        'id' => $lead->wpform_id,
                        'name' => $lead->wpform_name
                    ];
                }
            }
        }

        return response()->json([
            "error" => 0,
            "data" => $forms,
            "message" => "The website forms have been successfully retrieved"
        ], 200);

    }
    public function get_forms(Request $request)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $leads = Lead::byUser($user->id)->get();
        $forms = [];
        $usedForms = [];
        if($leads->count()) {
            foreach($leads as $lead) {
                if ( !in_array($lead->wpform_id, $usedForms) ) {
                    $usedForms[] = $lead->wpform_id;
                    $forms[] = [
                        'id' => $lead->wpform_id,
                        'name' => $lead->wpform_name
                    ];
                }
            }
        }

        return response()->json([
            "error" => 0,
            "data" => $forms,
            "message" => "The forms have been successfully retrieved"
        ], 200);
    }

    public function get_websites(Request $request)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }
        $websites = Website::byUser($user->id)->has('leads')->get();
        $websiteHas = [];
        foreach($websites as $website) {
            $websiteHas[] = [
                'id' => $website->id,
                'name' => $website->website_name
            ];
        }


        return response()->json([
            "error" => 0,
            "data" => $websiteHas,
            "message" => "The forms have been successfully retrieved"
        ], 200);
    }

    public function status(LeadStatusRequest $request, $id)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $lead = Lead::byUser($user->id)->whereId($id)->first();
        if (is_null($lead)) {
            return response()->json([
                "error" => 1,
                "message" => "Lead Not Found!"
            ], 404);
        }

        $lead->update([
            'status' => $request->status
        ]);

        return response()->json([
            "error" => 0,
            "data" => $lead,
            "message" => "Lead status has been successfully updated"
        ], 200);
    }

    public function view(Request $request, $id)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $lead = Lead::byUser($user->id)->whereId($id)->first();
        if (is_null($lead)) {
            return response()->json([
                "error" => 1,
                "message" => "Lead Not Found!"
            ], 404);
        }

        $lead->update([
            'is_viewed' => true
        ]);

        return response()->json([
            "error" => 0,
            "data" => $lead,
            "message" => "Lead has been successfully viewed"
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

        $lead = Lead::byUser($user->id)->whereId($id)->first();
        if (is_null($lead)) {
            return response()->json([
                "error" => 1,
                "message" => "Lead Not Found!"
            ], 404);
        }

        $form_data = json_decode($lead->form_data);
        if(!empty($form_data) && isset($form_data->data->file)) {
            foreach($form_data->data->file as $key => $value) {
                if (Storage::exists('/public/leads/' . $value->name)) {
                    Storage::delete('/public/leads/' . $value->name);
                }
            }
        }

        $lead->delete();

        return response()->json([
            "error" => 0,
            "message" => "Lead has been successfully deleted"
        ], 200);
    }

    public function bulk_delete(LeadBulkDeleteRequest $request)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $leads = Lead::byUser($user->id)->whereIn('id', $request->ids)->get();
        if (!$leads->count()) {
            return response()->json([
                "error" => 1,
                "message" => "Leads Not Found!"
            ], 404);
        }

        $deletedCount = 0;
        $failedToDelete = [];

        foreach($leads as $lead) {
            try {
                DB::beginTransaction();

                $form_data = json_decode($lead->form_data);
                if(!empty($form_data) && isset($form_data->data->file)) {
                    foreach($form_data->data->file as $key => $value) {
                        if (Storage::exists('/public/leads/' . $value->name)) {
                            Storage::delete('/public/leads/' . $value->name);
                        }
                    }
                }

                $lead->delete();
                $deletedCount++;

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $failedToDelete[] = $lead->id;
            }
        }

        $message = '';
        if ($deletedCount > 0) {
            $message .= $deletedCount . ' Leads deleted successfully.';
        }

        if (count($failedToDelete) > 0) {
            $message .= ' Failed to delete ' . count($failedToDelete) . ' leads.';
        }

        return response()->json([
            'error' => 0,
            'data' => [
                'failed_items' => $failedToDelete
            ],
            'message' => $message
        ], 200);
    }

    public function generate_pdf(Request $request) {
        $user = $this->resolveUser($request);
        $leads = Lead::byUser($user->id)->whereIn('id', $request->ids)->get();
        if (!$leads->count()) {
            return response()->json([
                "error" => 1,
                "message" => "Leads Not Found!"
            ], 404);
        }

        $pdf = Pdf::loadView('leadsPDF', ['leads' => $leads]);
        $filename = 'leads-'. Str::random(12) .'.pdf';
        $filePath = storage_path('app/public/leads_export/' . $filename);
        $pdf->save($filePath);

        return response()->json([
            "error" => 0,
            "data" => asset(Storage::url('leads_export/'.$filename)),
            "message" => "Leads PDF has been generated successfully"
        ], 200);
    }

    public function generate_excel(Request $request) {
        $user = $this->resolveUser($request);
        if(!$request->has('ids')) {
            return response()->json([
                "error" => 1,
                "message" => "Leads Not Found!"
            ], 404);
        }

        $leads = Lead::byUser($user->id)->whereIn('id', $request->ids)->get();
        if (!$leads->count()) {
            return response()->json([
                "error" => 1,
                "message" => "Leads Not Found!"
            ], 404);
        }

        $filename = 'leads-'. Str::random(12) .'.xlsx';
        $filePath = 'public/leads_export/' . $filename;

        if(count($request->ids) == 1) {
            $data = [];
            foreach($leads as $index => $lead) {
                $form_data = json_decode($lead->form_data);
                $data[$index][]['Lead Details'] = [
                    'key' => 'Lead Details'
                ];

                $data[$index][]['id'] = [
                    'key' => 'Lead ID',
                    'value' => '#' . ($lead->id > 9 ? $lead->id : '0'. $lead->id)
                ];

                $data[$index][]['wpform_name'] = [
                    'key' => 'Form Name',
                    'value' => $lead->wpform_name,
                ];

                $data[$index][]['status'] = [
                    'key' => 'Lead Status',
                    'value' => leadStatus($lead->status),
                ];

                $data[$index][]['created_at'] = [
                    'key' => 'Submitted on',
                    'value' => $lead->created_at->format('F j, Y'),
                ];

                $data[$index][]['User Information'] = [
                    'key' => 'User Information'
                ];

                $data[$index][]['visitor_ip'] = [
                    'key' => 'IP Address',
                    'value' => $form_data->visitor_info->ip,
                ];

                $data[$index][]['visitor_platform'] = [
                    'key' => 'Platform',
                    'value' => $form_data->visitor_info->platform,
                ];

                $data[$index][]['visitor_browser'] = [
                    'key' => 'Browser/OS',
                    'value' => $form_data->visitor_info->browser,
                ];

                $data[$index][]['visitor_ref_url'] = [
                    'key' => 'Referrer URL',
                    'value' => $form_data->visitor_info->ref_url,
                ];

                $data[$index][]['visitor_continent'] = [
                    'key' => 'Continent',
                    'value' => ($form_data->visitor_info->continent !== '' && $form_data->visitor_info->continent !== 'unknown') ? $form_data->visitor_info->continent : 'Not Available',
                ];

                $data[$index][]['visitor_country'] = [
                    'key' => 'Country',
                    'value' => ($form_data->visitor_info->country !== '' && $form_data->visitor_info->country !== 'unknown') ? $form_data->visitor_info->country : 'Not Available',
                ];

                $data[$index][]['visitor_country_code'] = [
                    'key' => 'Country Code',
                    'value' => ($form_data->visitor_info->country_code !== '' && $form_data->visitor_info->country_code !== 'unknown') ? $form_data->visitor_info->country_code : 'Not Available',
                ];

                $data[$index][]['visitor_state'] = [
                    'key' => 'State',
                    'value' => ($form_data->visitor_info->state !== '' && $form_data->visitor_info->state !== 'unknown') ? $form_data->visitor_info->state : 'Not Available',
                ];

                $data[$index][]['visitor_city'] = [
                    'key' => 'City',
                    'value' => ($form_data->visitor_info->city !== '' && $form_data->visitor_info->city !== 'unknown') ? $form_data->visitor_info->city : 'Not Available',
                ];

                if($form_data->data) {
                    $data[$index][]['Form Lead Details'] = [
                        'key' => 'Form Lead Details'
                    ];

                    foreach($form_data->data as $field => $item) {
                        if($field == 'checkbox-list') {
                            foreach($item as $key => $checkbox_list) {
                                $data[$index][][$key] = [
                                    'key' => formatText($key),
                                    'value' => implode(', ', (array) $checkbox_list),
                                ];
                            }
                        } elseif($field == 'file') {
                            foreach($item as $key => $file) {
                                $data[$index][][$key] = [
                                    'key' => formatText($key),
                                    'value' => $file->url,
                                ];
                            }
                        } else {
                            foreach($item as $key => $value) {
                                $data[$index][][$key] = [
                                    'key' => formatText($key),
                                    'value' => $value,
                                ];
                            }
                        }
                    }
                }
            }

            Excel::store(new LeadsExport($data), $filePath, null, MWExcel::XLSX, ['Content-Type' => 'text/xlsx']);
        } else {
            $data = [];
            $headings = [];
            $titles = [];

            foreach($leads as $index => $lead) {
                $form_data = json_decode($lead->form_data);
                $titles[$lead->wpform_id] = $lead->wpform_name;
                $headings[$lead->wpform_id] = [
                    'Lead ID',
                    'Form Name',
                    'Lead Status',
                    'Submitted on',
                    'IP Address',
                    'Platform',
                    'Browser/OS',
                    'Referrer URL',
                    'Continent',
                    'Country',
                    'Country Code',
                    'State',
                    'City',
                ];

                $data[$lead->wpform_id][$lead->id] = [
                    'lead_id' => '#' . ($lead->id > 9 ? $lead->id : '0'. $lead->id),
                    'wpform_name' => $lead->wpform_name,
                    'lead_status' => leadStatus($lead->status),
                    'submitted_on' => $lead->created_at->format('F j, Y'),
                    'visitor_ip' => $form_data->visitor_info->ip,
                    'visitor_platform' => $form_data->visitor_info->platform,
                    'visitor_browser' => $form_data->visitor_info->browser,
                    'visitor_ref_url' => $form_data->visitor_info->ref_url,
                    'visitor_continent' => ($form_data->visitor_info->continent !== '' && $form_data->visitor_info->continent !== 'unknown') ? $form_data->visitor_info->continent : 'Not Available',
                    'visitor_country' => ($form_data->visitor_info->country !== '' && $form_data->visitor_info->country !== 'unknown') ? $form_data->visitor_info->country : 'Not Available',
                    'visitor_country_code' => ($form_data->visitor_info->country_code !== '' && $form_data->visitor_info->country_code !== 'unknown') ? $form_data->visitor_info->country_code : 'Not Available',
                    'visitor_state' => ($form_data->visitor_info->state !== '' && $form_data->visitor_info->state !== 'unknown') ? $form_data->visitor_info->state : 'Not Available',
                    'visitor_city' => ($form_data->visitor_info->city !== '' && $form_data->visitor_info->city !== 'unknown') ? $form_data->visitor_info->city : 'Not Available',
                ];

                if($form_data->data) {
                    foreach($form_data->data as $field => $item) {
                        if($field == 'checkbox-list') {
                            foreach($item as $key => $checkbox_list) {
                                $headings[$lead->wpform_id][] = formatText($key);
                                $data[$lead->wpform_id][$lead->id][$key] = implode(', ', (array) $checkbox_list);
                            }
                        } elseif($field == 'file') {
                            foreach($item as $key => $file) {
                                $headings[$lead->wpform_id][] = formatText($key);
                                $data[$lead->wpform_id][$lead->id][$key] = $file->url;
                            }
                        } else {
                            foreach($item as $key => $value) {
                                $headings[$lead->wpform_id][] = formatText($key);
                                $data[$lead->wpform_id][$lead->id][$key] = $value;
                            }
                        }
                    }
                }
            }

            Excel::store(new LeadsExport2($data, $titles, $headings), $filePath, null, MWExcel::XLSX, ['Content-Type' => 'text/xlsx']);
        }

        return response()->json([
            "error" => 0,
            "data" => asset(Storage::url('leads_export/'.$filename)),
            "message" => "Leads excel sheet has been generated successfully"
        ], 200);
    }
}
