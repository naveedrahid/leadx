<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlockKeywordRequest extends FormRequest
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
            'website_id' => 'required|exists:websites,id',
            'form_id' => 'required|exists:customer_forms,id',
            'keywords' => 'required|array|min:1',
            'keywords.*' => 'exists:form_keywords,id',
        ];
    }
}
