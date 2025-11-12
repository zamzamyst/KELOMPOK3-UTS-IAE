<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackingController;

Route::get('trackings', [TrackingController::class, 'index']);
Route::post('trackings', [TrackingController::class, 'store']);
Route::get('trackings/{id}', [TrackingController::class, 'show']);
