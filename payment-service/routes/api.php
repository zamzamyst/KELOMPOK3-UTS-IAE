<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('payments', [PaymentController::class,'index']);
Route::post('payments', [PaymentController::class,'store']);
Route::get('payments/{payment}', [PaymentController::class,'show']);
Route::put('payments/{payment}', [PaymentController::class,'update']);
Route::delete('payments/{payment}', [PaymentController::class,'destroy']);
