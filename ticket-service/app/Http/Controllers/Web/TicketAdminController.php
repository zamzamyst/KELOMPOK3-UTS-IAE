<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Http;

class TicketAdminController extends Controller
{
    public function index()
    {
        $tickets = Ticket::orderBy('id','desc')->paginate(20);
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        // fetch schedules from bus-service
        $busBase = env('BUS_SERVICE_URL', 'http://127.0.0.1:8001');
        $resp = Http::get("{$busBase}/api/schedules");
        $schedules = $resp->ok() ? collect($resp->json())->map(function($s){ return (object)$s; }) : collect();
        return view('tickets.create', compact('schedules'));
    }

    public function store(Request $r)
    {
        $v = $r->validate([
            'schedule_id' => 'required|integer',
            'passenger_name' => 'required|string',
            'seat_count' => 'required|integer|min:1',
            'passenger_contact' => 'nullable|string'
        ]);

        // Call internal API to reserve seats (same logic as API flow)
        $busBase = env('BUS_SERVICE_URL', 'http://127.0.0.1:8001');
        $reserve = Http::put("{$busBase}/api/internal/schedules/{$v['schedule_id']}/reserve", ['seats' => $v['seat_count']]);
        if (!$reserve->ok()) {
            return back()->withErrors(['seat' => 'Failed to reserve seats: '.$reserve->body()])->withInput();
        }

        // get schedule price
        $scheduleResp = Http::get("{$busBase}/api/internal/schedules/{$v['schedule_id']}");
        $schedule = $scheduleResp->ok() ? $scheduleResp->json() : null;

        $ticket = Ticket::create([
            'ticket_number' => strtoupper(\Illuminate\Support\Str::random(8)),
            'schedule_id' => $v['schedule_id'],
            'passenger_name' => $v['passenger_name'],
            'passenger_contact' => $v['passenger_contact'] ?? null,
            'seat_count' => $v['seat_count'],
            'total_price' => $v['seat_count'] * ($schedule['price'] ?? 0),
            'status' => 'unpaid'
        ]);

        return redirect()->route('tickets.index')->with('success','Ticket created');
    }

    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $busBase = env('BUS_SERVICE_URL', 'http://127.0.0.1:8001');
        $scheduleResp = \Illuminate\Support\Facades\Http::get("{$busBase}/api/internal/schedules/{$ticket->schedule_id}");
        $price = $scheduleResp->ok() ? ($scheduleResp->json()['price'] ?? 0) : 0;
        $available = $scheduleResp->ok() ? ($scheduleResp->json()['available_seats'] ?? null) : null;
        return view('tickets.edit', compact('ticket','price','available'));
    }

    public function update(Request $r, Ticket $ticket)
    {
        $data = $r->validate([
            'passenger_name' => 'required|string',
            'passenger_contact' => 'nullable|string',
            'seat_count' => 'required|integer|min:1',
        ]);

        // Fetch schedule price to recalculate total
        $busBase = env('BUS_SERVICE_URL', 'http://127.0.0.1:8001');
        $scheduleResp = \Illuminate\Support\Facades\Http::get("{$busBase}/api/internal/schedules/{$ticket->schedule_id}");

        if ($scheduleResp->ok()) {
            $price = $scheduleResp->json()['price'] ?? 0;
        } else {
            // Fallback to previous per-seat price
            $prevSeats = max(1, (int)$ticket->seat_count);
            $price = $prevSeats > 0 ? ((float)$ticket->total_price / $prevSeats) : 0;
        }

        $ticket->passenger_name = $data['passenger_name'];
        $ticket->passenger_contact = $data['passenger_contact'] ?? null;
        $ticket->seat_count = (int)$data['seat_count'];
        $ticket->total_price = $price * (int)$data['seat_count'];
        $ticket->save();

        return redirect()->route('tickets.index')->with('success','Ticket updated');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success','Ticket deleted');
    }
}
