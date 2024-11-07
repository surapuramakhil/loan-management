<?php

namespace App\Http\Controllers\API;

use App\Models\Loan;
use App\Services\PaymentService;
use App\Http\Requests\PaymentRequest;
use Illuminate\Http\JsonResponse;
use App\Exceptions\PaymentProcessingException;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Process a payment
     *
     * @param PaymentRequest $request
     * @param Loan $loan
     * @return JsonResponse
     */
    public function process(PaymentRequest $request, Loan $loan): JsonResponse
    {
        try {
            $payment = $this->paymentService->payment($loan, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'data' => [
                    'payment_id' => $payment->id,
                    'amount_paid' => $payment->amount,
                    'remaining_loan_amount' => $loan->amount
                ]
            ], 201);
        } catch (PaymentProcessingException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}