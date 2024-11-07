<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'borrower_id' => ['required', 'exists:users,id', 'different:lender_id', Rule::notIn([Auth::id()])],
            'amount' => ['required', 'numeric', 'min:1', 'max:999999999.99'],
            'interest_rate' => ['required', 'numeric', 'min:5', 'max:100'],
            'duration_years' => ['required', 'integer', 'min:1', 'max:30']
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'The loan amount is required',
            'amount.numeric' => 'The loan amount must be a number',
            'amount.min' => 'The loan amount must be at least 1',
            'amount.max' => 'The loan amount cannot exceed 999,999,999.99',
            'interest_rate.required' => 'The interest rate is required',
            'interest_rate.numeric' => 'The interest rate must be a number',
            'interest_rate.min' => 'The interest rate must be at least 5',
            'interest_rate.max' => 'The interest rate cannot exceed 100',
            'duration_years.required' => 'The loan duration is required',
            'duration_years.integer' => 'The loan duration must be an integer',
            'duration_years.min' => 'The loan duration must be at least 1 year',
            'duration_years.max' => 'The loan duration cannot exceed 30 years',
            'borrower_id.required' => 'The borrower ID is required',
            'borrower_id.exists' => 'The borrower ID must exist in the users table',
            'borrower_id.different' => 'The borrower ID must be different from the lender ID and the authenticated user ID'
        ];
    }
}
