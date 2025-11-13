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

Route::put('schedules/{schedule}/reserve', [InternalController::class,'reserveSeats']);
