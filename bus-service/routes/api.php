<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusController;
use App\Http\Controllers\BusRouteController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\InternalController;

Route::apiResource('buses', BusController::class);
Route::apiResource('routes', BusRouteController::class);
Route::apiResource('schedules', ScheduleController::class);

Route::post('routes/{route}/assign-bus', [BusRouteController::class, 'assignBus']);

// Internal endpoints used by other services (ticket-service)
Route::get('internal/schedules/{id}', [InternalController::class, 'getSchedule']);
Route::put('internal/schedules/{id}/reserve', [InternalController::class,'reserveSeats']);

// backward-compatible route (kept for internal use as well)
Route::put('schedules/{schedule}/reserve', [InternalController::class,'reserveSeats']);
