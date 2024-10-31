<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1', 'max:999999999.99'],
            'interest_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'duration_years' => ['required', 'integer', 'min:1', 'max:30']
        ];
    }

    public function messages(): array
    {
        return [
            'amount.max' => 'The loan amount cannot exceed 999,999,999.99',
            'duration_years.max' => 'The loan duration cannot exceed 30 years'
        ];
    }
}