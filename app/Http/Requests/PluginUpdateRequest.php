<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class PluginUpdateRequest extends FormRequest
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
        return [
            "plugin_name" => "required|string",
            "plugin_url" => "nullable|url",
            "version" => "required|string",
            "plugin_file" => "nullable|mimes:zip",
            "documentation" => "nullable|url"
        ];
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
