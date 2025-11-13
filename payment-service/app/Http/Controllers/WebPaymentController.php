<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class WebPaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::latest()->paginate(20);
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        return view('payments.create');
    }

    // Note: we intentionally do not implement a local `store` that creates Payment directly.
    // The form posts to the API gateway at `/api/payment-service/payments` so that all
    // CRUD operations go through the gateway (port 4000) and the API routes in `api.php`.
}

