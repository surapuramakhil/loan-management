<?php

namespace App\Exceptions;

use Exception;

class PaymentProcessingException extends Exception
{
    // Custom exception for payment processing errors
    public function __construct(string $message = 'Payment Exception')
    {
        parent::__construct($message, 400);
    }

    public function render()
    {
        return response()->json([
            'message' => $this->getMessage(),
            'error' => 'Payment Exception'
        ], $this->getCode());
    }
}