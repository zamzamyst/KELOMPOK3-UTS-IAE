<?php
namespace App\Http\Controllers;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class TicketController extends Controller {
    public function index(){ return Ticket::all(); }

    public function store(Request $r){
        $v = $r->validate([
            'schedule_id'=>'required|integer',
            'passenger_name'=>'required|string',
            'seat_count'=>'required|integer|min:1',
            'passenger_contact'=>'nullable|string'
        ]);

        $busBase = env('BUS_SERVICE_URL', 'http://localhost:8001');
        $resp = Http::get("{$busBase}/api/internal/schedules/{$v['schedule_id']}");
        if (!$resp->ok()) return response()->json(['error'=>'Schedule not found'],404);
        $schedule = $resp->json();

        if ($schedule['available_seats'] < $v['seat_count']) return response()->json(['error'=>'Insufficient seats'],400);

        $reserve = Http::put("{$busBase}/api/internal/schedules/{$v['schedule_id']}/reserve", ['seats'=>$v['seat_count']]);
        if (!$reserve->ok()) return response()->json(['error'=>'Failed to reserve seats'],500);

        $ticket = Ticket::create([
            'ticket_number' => strtoupper(Str::random(8)),
            'schedule_id' => $v['schedule_id'],
            'passenger_name' => $v['passenger_name'],
            'passenger_contact' => $v['passenger_contact'] ?? null,
            'seat_count' => $v['seat_count'],
            'total_price' => $v['seat_count'] * $schedule['price'],
            'status' => 'unpaid'
        ]);

        return response()->json($ticket, 201);
    }

    public function show(Ticket $ticket){ return $ticket; }

    public function update(Request $r, Ticket $ticket){
        $data = $r->validate([
            'passenger_name' => 'sometimes|required|string',
            'passenger_contact' => 'sometimes|nullable|string',
            'seat_count' => 'sometimes|required|integer|min:1'
        ]);

        $ticket->fill($data);
        $ticket->save();

        return response()->json($ticket);
    }

    public function destroy(Ticket $ticket){
        $ticket->delete();
        return response()->json(['message'=>'Deleted']);
    }
}