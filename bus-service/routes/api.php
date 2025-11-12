<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusController;
use App\Http\Controllers\BusRouteController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\InternalController;

Route::apiResource('buses', BusController::class);
Route::apiResource('routes', BusRouteController::class);
Route::apiResource('schedules', ScheduleController::class);

Route::prefix('internal')->group(function(){
    Route::get('/buses/{id}', [BusController::class, 'show']);
    Route::get('schedules/{id}', [InternalController::class,'getSchedule']);
    Route::put('schedules/{id}/reserve', [InternalController::class,'reserveSeats']);
});
