<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackingController;

Route::name('api.')->group(function(){
	Route::get('trackings', [TrackingController::class, 'index']);
	Route::post('trackings', [TrackingController::class, 'store']);
	Route::get('trackings/{id}', [TrackingController::class, 'show']);
	Route::delete('trackings/{id}', [TrackingController::class, 'destroy']);
});
