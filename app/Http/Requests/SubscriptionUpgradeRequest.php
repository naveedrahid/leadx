<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class SubscriptionUpgradeRequest extends FormRequest
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
            'default_card' => 'required',
            'package' => 'required',
            'payment_method' => 'required',
            'websites' => 'required',
            'websites.*' => 'required'
        ];

        if(request()->get('default_card') === false) {
            $rule['card_holder_name'] = 'required';
            $rule['paymentMethodId'] = 'required';
        }

        return $rule;
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error' => 1,
            'data' => $validator->errors(),
            'message' => 'Validation Errors Found!'
        ], 422));
    }
}
