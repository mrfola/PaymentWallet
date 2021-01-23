<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'home_index'])->name('home');

Route::middleware('auth')->group(function () {
Route::get('/wallets', [App\Http\Controllers\PaymentWalletController::class, 'index'])->name('payment_wallet.index');
Route::post('/wallet', [App\Http\Controllers\PaymentWalletController::class, 'store'])->name('payment_wallet.store');
Route::delete('/wallet', [App\Http\Controllers\PaymentWalletController::class, 'destroy'])->name('payment_wallet.destroy');

Route::post('/pay-paystack', [App\Http\Controllers\PaystackPaymentController::class, 'redirectToGateway'])->name('pay-paystack');
Route::get('/payment/callback', [App\Http\Controllers\PaystackPaymentController::class, 'handleGatewayCallback'])->name('callback-paystack');
Route::post('/withdraw', [App\Http\Controllers\PaymentWalletController::class, 'withdraw_from_wallet'])->name('withdraw-from-wallet');
});
