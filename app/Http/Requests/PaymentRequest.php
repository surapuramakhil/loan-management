<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0.01'
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'The payment amount is required',
            'amount.numeric' => 'The payment amount must be a number',
            'amount.min' => 'The payment amount must be at least 0.01'
        ];
    }
}