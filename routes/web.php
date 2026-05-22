<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::prefix('admin')->middleware('auth')->group(function(){
    Route::post('/upload-csv',[AdminController::class,'insertCSV'])->name('admin.csv.upload');
});


Route::middleware('auth')->group(function(){
    Route::post('/payment/process',[PaymentController::class,'process'])->name('payment.process');
    Route::match(['get','post'],'/payment/success/{gateway}',
        [PaymentController::class,'success']
    )->name('payment.success');

    Route::match(['get','post'],'/payment/cancel/{gateway}',
        [PaymentController::class,'cancel']
    )->name('payment.cancel');
});