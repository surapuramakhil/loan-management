<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Http\Requests\LoanRequest;
use App\Services\LoanService;
use Illuminate\Http\JsonResponse;

class LoanController extends Controller
{

    /**
     * @var LoanService
     */
    private LoanService $loanService;

    /**
     * LoanController constructor
     * 
     * @param LoanService $loanService
     */
    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }


    /**
     * Display a listing of loans
     */
    public function index(): JsonResponse
    {
        $loans = Loan::active()->get();
        return response()->json(['data' => $loans]);
    }

    /**
     * Display the specified loan
     */
    public function show(Loan $loan): JsonResponse
    {
        return response()->json(['data' => $loan]);
    }

    /**
     * create a new loan
     */
    public function store(LoanRequest $request): JsonResponse
    {
        $loan = $this->loanService->createLoan($request->validated());
        return response()->json(['data' => $loan], 201);
    }

    /**
     * Update the specified loan
     */
    public function update(LoanRequest $request, Loan $loan): JsonResponse
    {
        $this->loanService->updateLoan($loan, $request->validated());
        return response()->json(['data' => $loan]);
    }

    /**
     * Remove the specified loan
     */
    public function destroy(Loan $loan): JsonResponse
    {
        $this->loanService->deleteLoan($loan);
        return response()->json('Load deleted', 204);
    }
}