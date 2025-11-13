<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebPaymentController;


Route::get('/', function () {
    return redirect('http://127.0.0.1:8000');
});

// simple web UI for payments so gateway UI links resolve
Route::resource('payments', WebPaymentController::class);
