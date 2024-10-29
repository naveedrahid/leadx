<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CouponStoreRequest extends FormRequest
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
            "code" => "required|unique:coupons,code",
            "type" => "required",
            "amount" => "required",
            "max_uses" => "required",
            "max_uses_user" => "required",
            "duration" => "required",
            "expires_at" => "required"
        ];

        if(request()->has('duration') && request()->get('duration') == 'repeating') {
            $rule['duration_month'] = 'required';
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
