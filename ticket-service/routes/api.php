<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\InternalController;

Route::apiResource('tickets', TicketController::class);

Route::prefix('internal')->group(function(){
    Route::get('tickets/{id}', [InternalController::class,'show']);
    Route::put('tickets/{id}/status', [InternalController::class,'updateStatus']);
    Route::put('tickets/{id}/amount', [InternalController::class,'updateAmount']);
});
