<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiPaginate;
use App\Http\Resources\LeadResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\{
    LeadStoreRequest,
    LeadStatusRequest,
    LeadBulkDeleteRequest
};
use App\Models\{BlockKeyword, CustomerForm, FormKeyword, FormSettingKeyword, Lead, Website, License, Subscription};

use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LeadsExport;
use App\Exports\LeadsExport2;
use Maatwebsite\Excel\Excel as MWExcel;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class SpamKeywordController extends Controller
{
    use ApiPaginate;

    protected $user;
    protected $license;
    protected $website;

    public function __construct(Request $request)
    {
        $headers = $request->header();
        if (isset($headers['licensekey'][0])) {
            $license = License::bylicense($headers['licensekey'][0])->status('active')->first();
            if (!is_null($license)) {
                $this->license = $license;
                $this->user = $license->user;
            }
        }

        if (isset($headers['websiteurl'][0])) {
            $websiteurl = rtrim($headers['websiteurl'][0], '/');
            $website = Website::where('website_url', 'LIKE', '%' . $websiteurl . '%')->status('active')->first();
            if (!is_null($website)) {
                $this->website = $website;
            }
        }
    }

    public function get_count(Request $request)
    {
        $count = Lead::filterLeads($request)->count();
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
        $order = (object) [
            'orderby' => $request->filled('orderby') ? $request->orderby : 'id',
            'order' => $request->filled('order') ? $request->order : 'DESC',
        ];


        $leadsQuery = Lead::with(['user', 'website', 'spam_keywords', 'spam_keyword_lists'])->byUser(auth()->id())->where('is_spam', 1)->filterLeads($request)->orderBy($order->orderby, $order->order);

        if ($request->website_id) {
            $leadsQuery->where('website_id', $request->website_id);
        }
        if ($request->filled('perpage')) {
            $leads = $leadsQuery->paginate($request->perpage);
        } else {
            if ($request->filled('limit')) {
                $leadsQuery->limit($request->limit);
            }

            $leads = $leadsQuery->get();
        }


        $response = [
            "error" => 0,
            "data" => $request->filled('perpage') ? $leads->items() : $leads,
            "message" => "Leads have been successfully retrieved"
        ];

        if ($request->filled('perpage')) {
            $response['paginate'] = $this->paginate($leads);
        }
        return response()->json($response, 200);
    }


    // public function updateOrCreate(Request $request)
    // {
    //     $rawData = $request->getContent();
    //     preg_match('/name="form_id"\s+(.*?)\s+--/s', $rawData, $formId);
    //     preg_match('/name="form_name"\s+(.*?)\s+--/s', $rawData, $formName);
    //     preg_match('/name="user_id"\s+(.*?)\s+--/s', $rawData, $userId);
    //     preg_match('/name="setting"\s+(.*?)\s+--/s', $rawData, $settings);

    //     $cleanData = [
    //         'form_id' => trim($formId[1] ?? null),
    //         'form_name' => trim($formName[1] ?? null),
    //     ];


    //     $cleanedSettings = stripslashes($settings[1]);
    //     $decodedSettings = json_decode($cleanedSettings, true);
    //     if (isset($decodedSettings['keyword_block'])) {
    //         $cleanData['keywords'] = $decodedSettings['keyword_block'];
    //     }

    //     if ($this->website) {
    //         $cleanData['website_id'] = $this->website->id;
    //          $cleanData['user_id'] = $this->website->user_id;
    //     }

    //     $FormSetting = BlockKeyword::updateOrCreate(["form_id" => $cleanData['form_id'], "user_id" => $cleanData["user_id"]], $cleanData);
    //     return response()->json($FormSetting);
    // }
    public function updateOrCreate(Request $request)
    {
        $payload = $request->all();
        if (empty($payload)) {
            $raw = $request->getContent();
            $json = json_decode($raw, true);
            if (is_array($json)) $payload = $json;
            else parse_str($raw, $payload);
        }

        $website = $this->website ?: (isset($payload['website_id']) ? Website::find((int)$payload['website_id']) : null);
        if (!$website) {
            return response()->json(['message' => 'website not resolved'], 422);
        }
        $websiteId = (int)$website->id;

        $formId  = (int)($payload['form_id'] ?? 0);
        if (!$formId && !empty($payload['form_key'])) {
            $formId = (int) CustomerForm::where('website_id', $websiteId)
                ->where('form_key', $payload['form_key'])
                ->value('id');
        }
        if (!$formId) {
            return response()->json(['message' => 'form_id missing/invalid'], 422);
        }

        $settings = $payload['settings'] ?? $payload['setting'] ?? null;
        if (is_string($settings)) {
            $decoded = json_decode(stripslashes($settings), true);
            $settings = is_array($decoded) ? $decoded : [];
        } elseif (!is_array($settings)) {
            $settings = [];
        }

        $keywordsIn = $payload['keywords'] ?? Arr::get($settings, 'keyword_block', []);
        if (is_string($keywordsIn)) {
            $maybe = json_decode($keywordsIn, true);
            $keywordsIn = is_array($maybe) ? $maybe : array_filter(array_map('trim', explode(',', $keywordsIn)));
        }
        if (!is_array($keywordsIn)) $keywordsIn = [];

        $keywordIds = [];
        foreach ($keywordsIn as $k) {
            if ($k === null) continue;

            if (is_int($k) || (is_string($k) && ctype_digit($k))) {
                $id = (int)$k;
                if ($id > 0) $keywordIds[] = $id;
                continue;
            }

            $kw = trim((string)$k);
            if ($kw === '') continue;
            $fk = FormKeyword::firstOrCreate(
                ['keyword' => $kw],
                ['created_by' => optional($this->user)->id, 'status' => 'active']
            );
            $keywordIds[] = (int)$fk->id;
        }
        $keywordIds = array_values(array_unique($keywordIds));

        $data = [
            'user_id'    => optional($this->user)->id ?: $website->user_id ?: auth()->id(),
            'website_id' => $websiteId,
            'form_id'    => $formId,
            'keywords'   => $keywordIds,
            'is_blocked' => !empty($keywordIds),
        ];

        $lookup = ['website_id' => $websiteId, 'form_id' => $formId];
        $record = BlockKeyword::updateOrCreate($lookup, $data)->refresh();

        return response()->json([
            'message' => 'Block keywords updated.',
            'data'    => new \App\Http\Resources\BlockKeywordResource($record),
        ], 200);
    }

    public function selectBoxKeyword(Request $request)
    {
        $loadKeyword = FormKeyword::whereNotNull('keyword')
            ->get(['id', 'keyword'])
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'keyword' => $item->keyword,
                ];
            })
            ->values();

        return response()->json(["keyword_block" => $loadKeyword]);
    }

    public function get_by(Request $request, $id)
    {
        $lead = Lead::byUser($this->user->id)->whereId($id)->first();
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


    public function status(LeadStatusRequest $request, $id)
    {
        $lead = Lead::byUser($this->user->id)->whereId($id)->first();
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
        $lead = Lead::byUser($this->user->id)->whereId($id)->first();
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
        $lead = Lead::byUser($this->user->id)->whereId($id)->first();
        if (is_null($lead)) {
            return response()->json([
                "error" => 1,
                "message" => "Lead Not Found!"
            ], 404);
        }

        $form_data = json_decode($lead->form_data);
        if (!empty($form_data) && isset($form_data->data->file)) {
            foreach ($form_data->data->file as $key => $value) {
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
        $leads = Lead::byUser($this->user->id)->whereIn('id', $request->ids)->get();
        if (!$leads->count()) {
            return response()->json([
                "error" => 1,
                "message" => "Leads Not Found!"
            ], 404);
        }

        foreach ($leads as $lead) {
            $form_data = json_decode($lead->form_data);
            if (!empty($form_data) && isset($form_data->data->file)) {
                foreach ($form_data->data->file as $key => $value) {
                    if (Storage::exists('/public/leads/' . $value->name)) {
                        Storage::delete('/public/leads/' . $value->name);
                    }
                }
            }
        }

        Lead::byUser($this->user->id)->whereIn('id', $request->ids)->delete();
        return response()->json([
            "error" => 0,
            "message" => "Leads has been successfully deleted"
        ], 200);
    }

    public function generate_pdf(Request $request)
    {
        $user = $this->user;
        $leads = Lead::byUser($user->id)->whereIn('id', $request->ids)->get();
        if (!$leads->count()) {
            return response()->json([
                "error" => 1,
                "message" => "Leads Not Found!"
            ], 404);
        }

        $pdf = Pdf::loadView('leadsPDF', ['leads' => $leads]);
        $filename = 'leads-' . Str::random(12) . '.pdf';
        $filePath = storage_path('app/public/leads_export/' . $filename);
        $pdf->save($filePath);

        return response()->json([
            "error" => 0,
            "data" => asset(Storage::url('leads_export/' . $filename)),
            "message" => "Leads PDF has been generated successfully"
        ], 200);
    }

    public function generate_excel(Request $request)
    {
        $user = $this->user;
        $leads = Lead::byUser($user->id)->whereIn('id', $request->ids)->get();
        if (!$leads->count()) {
            return response()->json([
                "error" => 1,
                "message" => "Leads Not Found!"
            ], 404);
        }

        $filename = 'leads-' . Str::random(12) . '.xlsx';
        $filePath = 'public/leads_export/' . $filename;

        if (count($request->ids) == 1) {
            $data = [];
            foreach ($leads as $index => $lead) {
                $form_data = json_decode($lead->form_data);
                $data[$index][]['Lead Details'] = [
                    'key' => 'Lead Details'
                ];

                $data[$index][]['id'] = [
                    'key' => 'Lead ID',
                    'value' => '#' . ($lead->id > 9 ? $lead->id : '0' . $lead->id)
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

                if ($form_data->data) {
                    $data[$index][]['Form Lead Details'] = [
                        'key' => 'Form Lead Details'
                    ];

                    foreach ($form_data->data as $field => $item) {
                        if ($field == 'checkbox-list') {
                            foreach ($item as $key => $checkbox_list) {
                                $data[$index][][$key] = [
                                    'key' => formatText($key),
                                    'value' => implode(', ', (array) $checkbox_list),
                                ];
                            }
                        } elseif ($field == 'file') {
                            foreach ($item as $key => $file) {
                                $data[$index][][$key] = [
                                    'key' => formatText($key),
                                    'value' => $file->url,
                                ];
                            }
                        } else {
                            foreach ($item as $key => $value) {
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

            foreach ($leads as $index => $lead) {
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
                    'lead_id' => '#' . ($lead->id > 9 ? $lead->id : '0' . $lead->id),
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

                if ($form_data->data) {
                    foreach ($form_data->data as $field => $item) {
                        if ($field == 'checkbox-list') {
                            foreach ($item as $key => $checkbox_list) {
                                $headings[$lead->wpform_id][] = formatText($key);
                                $data[$lead->wpform_id][$lead->id][$key] = implode(', ', (array) $checkbox_list);
                            }
                        } elseif ($field == 'file') {
                            foreach ($item as $key => $file) {
                                $headings[$lead->wpform_id][] = formatText($key);
                                $data[$lead->wpform_id][$lead->id][$key] = $file->url;
                            }
                        } else {
                            foreach ($item as $key => $value) {
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
            "data" => asset(Storage::url('leads_export/' . $filename)),
            "message" => "Leads excel sheet has been generated successfully"
        ], 200);
    }
}
