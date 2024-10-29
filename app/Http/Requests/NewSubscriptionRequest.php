<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\Package;

class NewSubscriptionRequest extends FormRequest
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
        $package = Package::whereId(request()->package)->status('active')->first();
        if (is_null($package)) {
            throw new HttpResponseException(response()->json([
                "error" => 1,
                "message" => "Package Not Found!"
            ], 404));
        }

        $rules = [
            "email" => "required|unique:users,email",
            "package" => "required",
            "websites" => "required",
            "websites.*.website_name" => "required|unique:websites,website_name",
            "websites.*.website_url" => "required|unique:websites,website_url",
            "payment_method" => "required"
        ];

        if(!$package->free_plan) {
            $rules['card_holder_name'] = 'required';
            $rules['paymentMethodId'] = 'required';
        } else {
            $rules['fullname'] = 'required';
        }

        return $rules;
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
