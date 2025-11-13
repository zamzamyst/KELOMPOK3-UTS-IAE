<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Bus;
use App\Models\BusRoute;
use Illuminate\Http\Request;

class ScheduleAdminController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['bus', 'route'])->get();
        return view('schedules.index', compact('schedules'));
    }

    public function create()
    {
        $buses = Bus::all();
        $routes = BusRoute::all();
        return view('schedules.create', compact('buses', 'routes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'departure_at' => 'required|date',
            'arrival_at' => 'nullable|date|after_or_equal:departure_at',
            'available_seats' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0'
        ]);

        Schedule::create($validated);
        return redirect()->route('schedules.index')->with('success', 'Schedule created successfully');
    }

    public function show(Schedule $schedule)
    {
        return view('schedules.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        $buses = Bus::all();
        $routes = BusRoute::all();
        return view('schedules.edit', compact('schedule', 'buses', 'routes'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'departure_at' => 'required|date',
            'arrival_at' => 'nullable|date|after_or_equal:departure_at',
            'available_seats' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0'
        ]);

        $schedule->update($validated);
        return redirect()->route('schedules.index')->with('success', 'Schedule updated successfully');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Schedule deleted successfully');
    }
}
