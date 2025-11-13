<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\BusAdminController;

Route::get('/', function () {
    return redirect('http://127.0.0.1:8000');
});

Route::resource('buses', BusAdminController::class);
