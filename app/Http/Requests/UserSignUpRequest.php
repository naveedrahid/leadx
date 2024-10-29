<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UserSignUpRequest extends FormRequest
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
            "first_name" => "required|string",
            "last_name" => "nullable|string",
            "profile_image" => "nullable|mimes:jpeg,jpg,png,gif|max:10000", // Maximum size of 10MB (10000 KB)
            "email" => "required|email|unique:users,email",
            "password" => "required|string|min:8|confirmed",
            "terms" => "accepted",
            "is_admin" => "boolean"
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
