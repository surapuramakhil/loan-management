<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    public function __construct(string $message = 'Unauthorized action')
    {
        parent::__construct($message, 403);
    }

    public function render()
    {
        return response()->json([
            'message' => $this->getMessage(),
            'error' => 'Unauthorized action'
        ], $this->getCode());
    }
}