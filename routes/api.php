<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user/{user_id}/generate-pay-id', [PaymentController::class, 'generatePaymentId']);
    Route::get('get-user-payment-id/{user_id}', [PaymentController::class, 'getPaymentIdByUser']);
    Route::get('payment-id/search/{paymentId}', [PaymentController::class, 'search']);
    Route::delete('payment-id/delete/{id}', [PaymentController::class, 'destroy']);
});
