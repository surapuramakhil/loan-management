<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Http\Requests\LoanRequest;
use Illuminate\Http\JsonResponse;

class LoanController extends Controller
{
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
        $loan = Loan::create($request->validated());
        return response()->json(['data' => $loan], 201);
    }

    /**
     * Update the specified loan
     */
    public function update(LoanRequest $request, Loan $loan): JsonResponse
    {
        $loan->update($request->validated());
        return response()->json(['data' => $loan]);
    }

    /**
     * Remove the specified loan
     */
    public function destroy(Loan $loan): JsonResponse
    {
        $loan->delete();
        return response()->json(null, 204);
    }
}