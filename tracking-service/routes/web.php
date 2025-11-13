<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebTrackingController;


Route::get('/', function () {
    return redirect('http://127.0.0.1:8000');
});

// simple web UI for trackings so gateway UI links resolve
Route::resource('trackings', WebTrackingController::class);


