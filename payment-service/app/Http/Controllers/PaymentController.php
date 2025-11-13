<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller {
    public function store(Request $r){
        $v = $r->validate(['ticket_id'=>'required|integer','amount'=>'required|numeric']);
        
        // Check if ticket already has a successful payment
        $existingPayment = Payment::where('ticket_id', $v['ticket_id'])->where('status', 'success')->first();
        if ($existingPayment) {
            return response()->json(['error'=>'Ticket already paid'],400);
        }
        
        // Get ticket info from ticket-service to validate amount
        $ticketBase = env('TICKET_SERVICE_URL','http://localhost:8002');
        $ticketResp = Http::get("{$ticketBase}/api/internal/tickets/{$v['ticket_id']}");
        
        if (!$ticketResp->ok()) {
            return response()->json(['error'=>'Ticket not found'],404);
        }
        
        $ticket = $ticketResp->json();
        
        // Validate ticket status is not already paid
        if ($ticket['status'] === 'paid') {
            return response()->json(['error'=>'Ticket is already paid'],400);
        }
        
        // Validate amount matches ticket price
        if ($v['amount'] != $ticket['total_price']) {
            return response()->json(['error'=>'Amount does not match ticket price. Expected: '.$ticket['total_price']],400);
        }
        
        // create payment record
        $pay = Payment::create(['ticket_id'=>$v['ticket_id'],'amount'=>$v['amount'],'status'=>'success']); // mock success

        // notify ticket-service to set status = paid
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

    public function update(Request $r, Payment $payment){
        $v = $r->validate(['amount'=>'nullable|numeric','status'=>'nullable|string']);
        
        $ticketBase = env('TICKET_SERVICE_URL','http://localhost:8002');
        
        // If updating amount, admin can change to any value
        if (isset($v['amount'])) {
            // Check if ticket exists
            $ticketResp = Http::get("{$ticketBase}/api/internal/tickets/{$payment->ticket_id}");
            if (!$ticketResp->ok()) {
                return response()->json(['error'=>'Ticket not found'],404);
            }
            
            // Check if amount is actually changing
            if ($v['amount'] != $payment->amount) {
                $payment->amount = $v['amount'];
                
                // Update ticket amount in ticket-service
                $amountUpdateResp = Http::put("{$ticketBase}/api/internal/tickets/{$payment->ticket_id}/amount", ['total_price' => $v['amount']]);
                if (!$amountUpdateResp->ok()) {
                    return response()->json(['error'=>'Failed to update ticket amount'],500);
                }
                
                // If amount changed and payment was success, revert ticket to confirmed (unpaid)
                if ($payment->status === 'success') {
                    $revertResp = Http::put("{$ticketBase}/api/internal/tickets/{$payment->ticket_id}/status", ['status' => 'confirmed']);
                    if (!$revertResp->ok()) {
                        return response()->json(['error'=>'Failed to revert ticket status'],500);
                    }
                }
            }
        }
        
        // If updating status
        if (isset($v['status'])) {
            $validStatuses = ['success', 'failed'];
            if (!in_array($v['status'], $validStatuses)) {
                return response()->json(['error'=>'Invalid status. Allowed: success, failed'],400);
            }
            
            // If changing to success, check if ticket already paid
            if ($v['status'] === 'success' && $payment->status !== 'success') {
                $existingPayment = Payment::where('ticket_id', $payment->ticket_id)
                    ->where('status', 'success')
                    ->where('id', '!=', $payment->id)
                    ->first();
                if ($existingPayment) {
                    return response()->json(['error'=>'Ticket already has a successful payment'],400);
                }
                
                // Update ticket status to paid
                $paidResp = Http::put("{$ticketBase}/api/internal/tickets/{$payment->ticket_id}/status", ['status' => 'paid']);
                if (!$paidResp->ok()) {
                    return response()->json(['error'=>'Failed to update ticket status'],500);
                }
            }
            // If changing from success to failed, revert ticket to confirmed
            elseif ($v['status'] === 'failed' && $payment->status === 'success') {
                $revertResp = Http::put("{$ticketBase}/api/internal/tickets/{$payment->ticket_id}/status", ['status' => 'confirmed']);
                if (!$revertResp->ok()) {
                    return response()->json(['error'=>'Failed to revert ticket status'],500);
                }
            }
            
            $payment->status = $v['status'];
        }
        
        $payment->save();
        return response()->json(['message'=>'Payment updated','payment'=>$payment]);
    }

    public function destroy(Payment $payment){
        $ticketBase = env('TICKET_SERVICE_URL','http://localhost:8002');
        $paymentStatus = $payment->status;
        $ticketId = $payment->ticket_id;
        
        // If deleting successful payment, revert ticket to confirmed (unpaid)
        if ($paymentStatus === 'success') {
            $revertResp = Http::put("{$ticketBase}/api/internal/tickets/{$ticketId}/status", ['status' => 'confirmed']);
            if (!$revertResp->ok()) {
                return response()->json(['error'=>'Failed to revert ticket status'],500);
            }
        }
        
        $payment->delete();
        return response()->json(['message'=>'Payment deleted successfully']);
    }
}
