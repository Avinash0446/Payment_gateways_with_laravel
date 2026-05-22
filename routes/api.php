<?php

use App\Http\Controllers\PaypalWebhookController;
use App\Http\Controllers\RazorpayWebhookController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/stripe/webhook',[StripeWebhookController::class,'handleWebhook']);
Route::post('/razorpay/webhook',[RazorpayWebhookController::class,'handleWebhook']);
Route::post('/paypal/webhook',[PaypalWebhookController::class,'handleWebhook']);
