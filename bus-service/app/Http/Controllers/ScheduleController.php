<?php
namespace App\Http\Controllers;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller {
    public function index(){ return Schedule::with(['bus','route'])->get(); }
    public function store(Request $r){
        $v = $r->validate([
            'bus_id'=>'required|exists:buses,id',
            'route_id'=>'required|exists:routes,id',
            'departure_at'=>'required|date',
            'arrival_at'=>'nullable|date',
            'available_seats'=>'required|integer',
            'price'=>'required|numeric'
        ]);
        return response()->json(Schedule::create($v), 201);
    }
    public function show(Schedule $schedule){ return $schedule->load(['bus','route']); }
    public function update(Request $r, Schedule $schedule){ $schedule->update($r->all()); return $schedule; }
    public function destroy(Schedule $schedule){ $schedule->delete(); return response()->json(['message'=>'Deleted']); }
}
