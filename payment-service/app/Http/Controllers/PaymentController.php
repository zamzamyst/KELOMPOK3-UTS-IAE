<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller {
    public function store(Request $r){
        $v = $r->validate(['ticket_id'=>'required|integer','amount'=>'required|numeric']);
        // create payment record
        $pay = Payment::create(['ticket_id'=>$v['ticket_id'],'amount'=>$v['amount'],'status'=>'success']); // mock success

        // notify ticket-service to set status = paid
        $ticketBase = env('TICKET_SERVICE_URL','http://localhost:8002');
        $resp = Http::put("{$ticketBase}/api/internal/tickets/{$v['ticket_id']}/status", ['status' => 'paid']);

        if (!$resp->ok()) {
            // fallback: mark payment failed (but here we simulate ok)
            $pay->status = 'failed';
            $pay->save();
            return response()->json(['error'=>'Failed to update ticket'],500);
        }
        return response()->json(['payment'=>$pay,'ticket_update'=>$resp->json()]);
    }

    public function index(){ return Payment::all(); }
    public function show(Payment $payment){ return $payment; }
}
