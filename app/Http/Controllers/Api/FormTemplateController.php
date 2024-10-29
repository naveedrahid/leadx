<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiPaginate;
use App\Models\{
    FormCategory,
    FormTemplate,
    FormMailSetting
};
use App\Http\Requests\{
    FormTemplateStoreRequest,
    FormTemplateUpdateRequest,
    FormTemplateStatusUpdateRequest,
    FormTemplateBulkDeleteRequest
};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FormTemplateController extends Controller
{
    use ApiPaginate;

    public function get_count(Request $request)
    {
        $count = FormTemplate::filterFormTemplates($request)->count();
        return response()->json([
            "error" => 0,
            "data" => [
                "count" => $count
            ],
            "message" => "Templates count have been successfully retrieved"
        ], 200);
    }

    public function get_all(Request $request)
    {
        $order = (object) [
            'orderby' => $request->filled('orderby') ? $request->orderby : 'id',
            'order' => $request->filled('order') ? $request->order : 'DESC',
        ];

        $formTemplatesQuery = FormTemplate::filter($request)->orderBy($order->orderby, $order->order);

        if ($request->filled('perpage')) {
            $form_templates = $formTemplatesQuery->paginate($request->perpage);
        } else {
            if($request->filled('limit')) {
                $formTemplatesQuery->limit($request->limit);
            }

            $form_templates = $formTemplatesQuery->get();
        }

        $form_templates->load('categories', 'mail_settings');

        $response = [
            "error" => 0,
            "data" => $request->filled('perpage') ? $form_templates->items() : $form_templates,
            "message" => "Templates have been successfully retrieved"
        ];

        if($request->filled('perpage')) {
            $response['paginate'] = $this->paginate($form_templates);
        }
        
        return response()->json($response, 200);
    }

    public function get_by(Request $request, $id)
    {
        $form_template = FormTemplate::whereId($id)->first();
        if (is_null($form_template)) {
            return response()->json([
                "error" => 1,
                "message" => "Template Not Found!"
            ], 404);
        }

        $form_template->load('categories', 'mail_settings');

        return response()->json([
            "error" => 0,
            "data" => $form_template,
            "message" => "Template have been successfully retrieved"
        ], 200);
    }

    public function generateRandomKey() {
        $number = mt_rand(100, 999) . date('hisYdm');
        if ($this->randomKeyExists($number)) {
            return $this->generateRandomKey();
        }
        return $number;
    }

    public function randomKeyExists($number) {
        $form_template = FormTemplate::where('form_key', $number)->first();
        return !is_null($form_template);
    }

    public function store(FormTemplateStoreRequest $request)
    {
        $form_name = $request->input('form_name', '');
        $template = $request->input('template', '');
        $custom_css = $request->input('css', '');
        $mails = json_decode($request->input('mails', ''));
        $mail2 = $request->input('mail2', 0);
        $messages = json_decode($request->input('messages', ''));
        $settings = json_decode($request->input('settings', ''));
        $categories = json_decode($request->input('categories', ''));

        $form_key = $this->generateRandomKey();

        $template_image = null;
        if ($request->hasFile('template_image')) {
            $file = $request->file('template_image');
            $template_image = time().uniqid().'.'.$file->getClientOriginalExtension();
            $file->storeAs('public/templates', $template_image);
        }

        try {
            DB::beginTransaction();

            $form_template = FormTemplate::create([
                'form_name' => $form_name,
                'form_key' => $form_key,
                'template' => $template,
                'custom_css' => $custom_css,
                'settings' => !empty($settings) ? json_encode($settings) : null,
                'messages' => !empty($messages) ? json_encode($messages) : null,
                'description' => $request->description,
                'template_image' => $template_image,
                'status' => 'active'
            ]);

            if(count($categories)) {
                $form_template->categories()->attach($categories);
            }

            if (count($mails)) {
                foreach ($mails as $index => $mail) {
                    if ($index > 1) break;
                    if ($mail2 == 0 && $index == 1) continue;

                    FormMailSetting::create([
                        'form_id' => $form_template->id,
                        'sender' => !empty($mail->mail->sender) ? json_encode($mail->mail->sender) : null,
                        'recipient' => !empty($mail->mail->recipient) ? json_encode($mail->mail->recipient) : null,
                        'replay_to' => !empty($mail->mail->replyTo) ? $mail->mail->replyTo : null,
                        'topic' => !empty($mail->mail->topic) ? $mail->mail->topic : null,
                        'cc' => !empty($mail->mail->cc) ? json_encode($mail->mail->cc) : null,
                        'bcc' => !empty($mail->mail->bcc) ? json_encode($mail->mail->bcc) : null,
                        'body' => !empty($mail->mail->body) ? $mail->mail->body : null,
                        'use_html' => $mail->mail->use_html,
                        'attachment' => !empty($mail->mail->attachment) ? json_encode($mail->mail->attachment) : null,
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "error" => 1,
                "message" => "Error: ". $e->getMessage()
            ], 400);
        }

        $form_template->load('categories', 'mail_settings');

        return response()->json([
            "error" => 0,
            "data" => $form_template,
            "message" => "Template has been successfully created"
        ], 200);
    }

    public function update(FormTemplateUpdateRequest $request, $id)
    {
        $form_template = FormTemplate::whereId($id)->first();
        if (is_null($form_template)) {
            return response()->json([
                "error" => 1,
                "message" => "Template Not Found!"
            ], 404);
        }

        $form_name = $request->input('form_name', $form_template->form_name);
        $template = $request->input('template', $form_template->template);
        $custom_css = $request->input('css', $form_template->css);
        $mails = json_decode($request->input('mails', $form_template->mails));
        $mail2 = $request->input('mail2', 0);
        $messages = json_decode($request->input('messages', $form_template->messages));
        $settings = json_decode($request->input('settings', $form_template->settings));
        $categories = json_decode($request->input('categories', ''));

        $template_image = $form_template->template_image;
        if($request->has('remove_template_image')) {
            if (Storage::exists('public/templates/' . $form_template->template_image)) {
                Storage::delete('public/templates/' . $form_template->template_image);
            }

            $template_image = null;
        }

        if ($request->hasFile('template_image')) {
            $file = $request->file('template_image');
            $template_image = time().uniqid().'.'.$file->getClientOriginalExtension();
            $file->storeAs('public/templates', $template_image);
            
            if (Storage::exists('public/templates/' . $form_template->template_image)) {
                Storage::delete('public/templates/' . $form_template->template_image);
            }
        }

        try {
            DB::beginTransaction();

            $form_template->update([
                'form_name' => $form_name,
                'template' => $template,
                'custom_css' => $custom_css,
                'settings' => !empty($settings) ? json_encode($settings) : '',
                'messages' => !empty($messages) ? json_encode($messages) : '',
                'description' => $request->description,
                'template_image' => $template_image
            ]);

            if(count($categories)) {
                $form_template->categories()->sync($categories);
            }

            if (count($mails)) {
                foreach ($mails as $index => $mail) {
                    if ($index > 1) break;

                    $form_mail_setting = FormMailSetting::where('id', $mail->id)->where('form_id', $form_template->id)->first();
                    
                    if ($mail2 == 0 && $index == 1) {
                        if(!is_null($form_mail_setting)) {
                            $form_mail_setting->delete();
                        }
                        
                        continue;
                    }

                    if(!is_null($form_mail_setting)) {
                        $form_mail_setting->update([
                            'sender' => !empty($mail->mail->sender) ? json_encode($mail->mail->sender) : '',
                            'recipient' => !empty($mail->mail->recipient) ? json_encode($mail->mail->recipient) : '',
                            'replay_to' => !empty($mail->mail->replyTo) ? $mail->mail->replyTo : '',
                            'topic' => !empty($mail->mail->topic) ? $mail->mail->topic : '',
                            'cc' => !empty($mail->mail->cc) ? json_encode($mail->mail->cc) : '',
                            'bcc' => !empty($mail->mail->bcc) ? json_encode($mail->mail->bcc) : '',
                            'body' => !empty($mail->mail->body) ? $mail->mail->body : '',
                            'use_html' => $mail->mail->use_html,
                            'attachment' => !empty($mail->mail->attachment) ? json_encode($mail->mail->attachment) : '',
                        ]);
                    } else {
                        FormMailSetting::create([
                            'form_id' => $form_template->id,
                            'sender' => !empty($mail->mail->sender) ? json_encode($mail->mail->sender) : '',
                            'recipient' => !empty($mail->mail->recipient) ? json_encode($mail->mail->recipient) : '',
                            'replay_to' => !empty($mail->mail->replyTo) ? $mail->mail->replyTo : '',
                            'topic' => !empty($mail->mail->topic) ? $mail->mail->topic : '',
                            'cc' => !empty($mail->mail->cc) ? json_encode($mail->mail->cc) : '',
                            'bcc' => !empty($mail->mail->bcc) ? json_encode($mail->mail->bcc) : '',
                            'body' => !empty($mail->mail->body) ? $mail->mail->body : '',
                            'use_html' => $mail->mail->use_html,
                            'attachment' => !empty($mail->mail->attachment) ? json_encode($mail->mail->attachment) : '',
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "error" => 1,
                "message" => "Error: ". $e->getMessage()
            ], 400);
        }

        $form_template->load('categories', 'mail_settings');

        return response()->json([
            "error" => 0,
            "data" => $form_template,
            "message" => "Template has been successfully updated"
        ], 200);
    }

    public function status(FormTemplateStatusUpdateRequest $request, $id)
    {
        $form_template = FormTemplate::whereId($id)->first();
        if (is_null($form_template)) {
            return response()->json([
                "error" => 1,
                "message" => "Template Not Found!"
            ], 404);
        }

        $form_template->update([
            'status' => $request->status
        ]);

        $form_template->load('categories', 'mail_settings');

        return response()->json([
            "error" => 0,
            "data" => $form_template,
            "message" => "Template status has been successfully updated"
        ], 200);
    }

    public function delete(Request $request, $id)
    {
        $form_template = FormTemplate::whereId($id)->first();
        if (is_null($form_template)) {
            return response()->json([
                "error" => 1,
                "message" => "Template Not Found!"
            ], 404);
        }

        $form_template_image = $form_template->template_image;

        try {
            DB::beginTransaction();

            
            $form_template->categories()->detach();
            $form_template->mail_settings()->delete();
            $form_template->delete();
            
            if (Storage::exists('public/templates/' . $form_template_image)) {
                Storage::delete('public/templates/' . $form_template_image);
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
            "message" => "Template has been successfully deleted"
        ], 200);
    }

    public function bulk_delete(FormTemplateBulkDeleteRequest $request)
    {
        $form_templates = FormTemplate::whereIn('id', $request->ids)->get();
        if (!$form_templates->count()) {
            return response()->json([
                "error" => 1,
                "message" => "Templates Not Found!"
            ], 404);
        }

        $deletedCount = 0;
        $failedToDelete = [];

        foreach($form_templates as $form_template) {
            $form_template_image = $form_template->template_image;

            try {
                DB::beginTransaction();

                $form_template->categories()->detach();
                $form_template->mail_settings()->delete();
                $form_template->delete();

                if (Storage::exists('public/templates/' . $form_template_image)) {
                    Storage::delete('public/templates/' . $form_template_image);
                }
                
                $deletedCount++;
                
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $failedToDelete[] = $form_template->id;
            }
        }
            
        $message = '';
        if ($deletedCount > 0) {
            $message .= $deletedCount . ' Templates deleted successfully.';
        }

        if (count($failedToDelete) > 0) {
            $message .= ' Failed to delete ' . count($failedToDelete) . ' templates.';
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
