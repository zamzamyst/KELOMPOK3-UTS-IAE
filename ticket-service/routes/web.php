<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\TicketAdminController;


Route::get('/', function () {
    return redirect('http://127.0.0.1:8000');
});

Route::resource('tickets', TicketAdminController::class);
