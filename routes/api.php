<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/accounts', [AccountController::class, 'index']);
    Route::post('/accounts', [AccountController::class, 'store']);
    Route::delete('/accounts/{accountId}', [AccountController::class, 'delete']);
    Route::get('/accounts/{accountId}', [AccountController::class, 'details']);

    Route::post('/transact', [TransactionController::class, 'transact']);
    Route::post('/transfer', [TransactionController::class, 'transfer']);
});

