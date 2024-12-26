<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');

});


Route::get('/stripeform/{payment}', [PaymentController::class,'stripeform'])->name('stripeform');


Route::post('/payments/{payment_id}/charge', [PaymentController::class, 'createCharge'])->name('payments.charge');


