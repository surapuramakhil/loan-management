<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Loan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use App\Exceptions\PaymentProcessingException;

class PaymentService
{
    /**
     * Process a payment for a loan
     *
     * @param Loan $loan
     * @param array $paymentData
     * @return Payment
     * @throws InvalidArgumentException|PaymentProcessingException
     */
    public function payment(Loan $loan, array $paymentData): Payment
    {
        // Validate payment amount
        if (!isset($paymentData['amount']) || $paymentData['amount'] <= 0) {
            throw new InvalidArgumentException('Invalid payment amount');
        }

        // Validate if payment amount is not greater than remaining loan amount
        if ($paymentData['amount'] > $loan->amount) {
            throw new InvalidArgumentException('Payment amount cannot exceed loan amount');
        }

        try {
            return DB::transaction(function () use ($loan, $paymentData) {
                // Create payment record
                $payment = new Payment([
                    'loan_id' => $loan->id,
                    'amount' => $paymentData['amount'],
                    'status' => Payment::STATUS_COMPLETED,
                ]);

                $payment->save();

                // Update loan amount
                $loan->amount = $loan->amount - $paymentData['amount'];
                $loan->save();

                return $payment;
            });
        } catch (\Exception $e) {
            throw new PaymentProcessingException('Failed to process payment: ' . $e->getMessage());
        }
    }
}