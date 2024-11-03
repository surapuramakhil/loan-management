<?php

namespace App\Services;

use App\Models\Loan;
use App\Exceptions\UnauthorizedException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Class LoanService
 * 
 * Handles business logic for loan operations
 * 
 * @package App\Services
 */
class LoanService
{
    /**
     * Get all loans with related data
     * 
     * @return Collection
     */
    public function getAllLoans(): Collection
    {
        return Loan::with(['lender:id,name', 'borrower:id,name'])
            ->latest()
            ->get();
    }

    /**
     * Get detailed loan information
     * 
     * @param Loan $loan
     * @return Loan
     */
    public function getLoanDetails(Loan $loan): Loan
    {
        return $loan->load(['lender:id,name', 'borrower:id,name']);
    }

    /**
     * Create a new loan
     * 
     * @param array $validatedData
     * @return Loan
     */
    public function createLoan(array $validatedData): Loan
    {
        Log::info('User ' . Auth::id() . ' is creating a new loan');
        $validatedData['lender_id'] = Auth::id();
        $loan = Loan::create($validatedData);
        
        return $this->getLoanDetails($loan);
    }

    /**
     * Update an existing loan
     * 
     * @param Loan $loan
     * @param array $validatedData
     * @return Loan
     * @throws UnauthorizedException
     */
    public function updateLoan(Loan $loan, array $validatedData): Loan
    {
        $this->checkLoanOwnership($loan);
        
        $loan->update($validatedData);
        
        return $this->getLoanDetails($loan);
    }

    /**
     * Delete a loan
     * 
     * @param Loan $loan
     * @throws UnauthorizedException
     */
    public function deleteLoan(Loan $loan): void
    {
        $this->checkLoanOwnership($loan);
        $loan->delete();
    }

    /**
     * Check if the authenticated user owns the loan
     * 
     * @param Loan $loan
     * @throws UnauthorizedException
     */
    private function checkLoanOwnership(Loan $loan): void
    {
        if ($loan->lender_id !== Auth::id()) {
            Log::info('Unauthorized access attempt by user: ' . Auth::id());
            throw new UnauthorizedException('You are not authorized to perform this action on the loan');
        }
    }
}