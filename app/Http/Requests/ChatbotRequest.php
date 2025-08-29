<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatbotRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isCreate = !$this->route('chatbot');

        return [
            'website_id'           => [$isCreate ? 'required' : 'sometimes', 'integer'],
            'name'                 => 'required|string|max:120',
            'is_active'            => 'boolean',
            'system_prompt'        => 'nullable|string',
            'bubble_message'       => 'nullable|string|max:255',
            'welcome_message'      => 'nullable|string|max:255',
            'connect_message'      => 'nullable|string|max:255',
            'offline_message'      => 'nullable|string|max:255',
            'interaction_type'     => 'sometimes|integer|in:0,1,2',
            'language'             => 'sometimes|string|max:10',
            'do_not_go_beyond'     => 'boolean',
            'model'                => 'nullable|string|max:100',
            'temperature'          => 'sometimes|numeric|min:0|max:2',
            'top_k'                => 'sometimes|integer|min:1|max:10',
            'confidence_threshold' => 'sometimes|numeric|min:0|max:1',
            'avatar_type'          => 'nullable|string|max:20',
            'avatar_url'           => 'nullable|string|max:255',
            'logo_url'             => 'nullable|string|max:255',
            'color_accent'         => 'nullable|string|max:7',
            'show_logo'            => 'boolean',
            'show_datetime'        => 'boolean',
            'transparent_trigger'  => 'boolean',
            'trigger_avatar_size'  => 'sometimes|integer|min:20|max:200',
            'position'             => 'sometimes|string|in:left,right',
            'footer_link'          => 'nullable|string|max:255',
            'custom_css'           => 'nullable|string',
            'settings'             => 'nullable|array',
            'iframe_width'         => 'sometimes|integer|min:320|max:2000',
            'iframe_height'        => 'sometimes|integer|min:400|max:3000',
            'domain_allowlist'     => 'nullable|array',
            'moderation_on'        => 'boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        foreach (
            [
                'is_active',
                'do_not_go_beyond',
                'show_logo',
                'show_datetime',
                'transparent_trigger',
                'moderation_on'
            ] as $b
        ) {
            if ($this->has($b)) {
                $this->merge([$b => filter_var($this->input($b), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false]);
            }
        }

        if ($this->has('settings') && is_string($this->settings)) {
            $json = json_decode($this->settings, true);
            if (json_last_error() === JSON_ERROR_NONE) $this->merge(['settings' => $json]);
        }
        if ($this->has('domain_allowlist') && is_string($this->domain_allowlist)) {
            $json = json_decode($this->domain_allowlist, true);
            if (json_last_error() === JSON_ERROR_NONE) $this->merge(['domain_allowlist' => $json]);
        }
    }
}
