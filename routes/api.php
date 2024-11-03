<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LoanController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('loans', [LoanController::class, 'index']);
Route::get('loans/{loan}', [LoanController::class, 'show']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::post('loans', [LoanController::class, 'store']);
    Route::put('loans/{loan}', [LoanController::class, 'update']);
    Route::delete('loans/{loan}', [LoanController::class, 'destroy']);
});
