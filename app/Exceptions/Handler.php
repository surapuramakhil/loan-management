<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use App\Exceptions\UnauthorizedException;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
            Log::error($e->getMessage(), ['exception' => $e]);
        });

        // Handle UnauthorizedException
        $this->renderable(function (UnauthorizedException $e) {
            return $e->render();
        });

        // Handle AuthenticationException
        $this->renderable(function (AuthenticationException $e) {
            return response()->json([
                'message' => 'Unauthenticated',
                'error' => 'Authentication required'
            ], 401);
        });
    }
}