<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerFormRequest extends FormRequest
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
        return [
            'form_name' => 'required|string|max:255',
            'form_key' => 'required|string|max:255|unique:customer_forms,form_key',
            'website_id' => 'nullable|integer|exists:websites,id',
            'template' => 'nullable|string',
            'custom_css' => 'nullable|string',
            'settings' => 'nullable|string',
            'messages' => 'nullable|string',
            'description' => 'nullable|string',
            'template_image' => 'nullable|string',
            'status' => 'in:active,deactive',
        ];
    }
}
