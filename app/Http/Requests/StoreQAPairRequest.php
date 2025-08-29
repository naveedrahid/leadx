<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQAPairRequest extends FormRequest
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
            'qa_pairs'              => 'required|array|min:1',
            'qa_pairs.*.question'   => 'required|string|max:2000',
            'qa_pairs.*.answer'     => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'qa_pairs.required'         => 'The Q&A pairs field is required.',
        ];
    }
}
