<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\BusAdminController;
use App\Http\Controllers\Web\RouteAdminController;
use App\Http\Controllers\Web\ScheduleAdminController;

Route::get('/', function () {
    return redirect('http://127.0.0.1:8000');
});

Route::resource('buses', BusAdminController::class);
Route::resource('routes', RouteAdminController::class);
Route::resource('schedules', ScheduleAdminController::class);
