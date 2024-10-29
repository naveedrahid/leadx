<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class PackageUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rule = [
            "title" => "required",
            "features.*" => "required"
        ];

        if(request()->get('unlimited_websites') == "0") {
            $rule['website_limit'] = 'required';
        }

        if(request()->get('unlimited_leads') == "0") {
            $rule['lead_limit'] = 'required';
        }

        return $rule;
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            "error" => 1,
            "data" => $validator->errors(),
            "message" => "Validation Errors Found!"
        ], 422));
    }
}
