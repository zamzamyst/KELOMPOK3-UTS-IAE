<?php
namespace App\Http\Controllers;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller {
    public function index()
    { 
        return Schedule::with(['bus','route'])->get(); 
    }

    public function store(Request $r)
    {
        $v = $r->validate([
            'bus_id'=>'nullable|exists:buses,id',
            'route_id'=>'required|exists:routes,id',
            'departure_at'=>'required|date',
            'arrival_at'=>'nullable|date',
            'available_seats'=>'required|integer',
            'price'=>'required|numeric'
        ]);

        $busId = $v['bus_id'] ?? null;
        $routeId = $v['route_id'];

        // If bus_id provided, ensure it belongs to the route
        if ($busId) {
            $assignedRouteId = null;
            $bus = \App\Models\Bus::find($busId);
            if ($bus && isset($bus->route_id) && $bus->route_id) {
                $assignedRouteId = (int)$bus->route_id;
            } else {
                $pivot = \Illuminate\Support\Facades\DB::table('bus_route')->where('bus_id', $busId)->first();
                if ($pivot && isset($pivot->route_id)) $assignedRouteId = (int)$pivot->route_id;
            }

            if ($assignedRouteId && $assignedRouteId !== (int)$routeId) {
                return response()->json(['message'=>'bus_id does not belong to the provided route_id'], 422);
            }
        } else {
            // No bus_id provided: attempt to pick a bus assigned to the route
            $busId = \Illuminate\Support\Facades\DB::table('bus_route')->where('route_id', $routeId)->value('bus_id');
            if (!$busId) {
                // fallback to route_id column on buses
                $busId = \Illuminate\Support\Facades\DB::table('buses')->where('route_id', $routeId)->value('id');
            }
            if (!$busId) {
                return response()->json(['message'=>'No bus assigned to this route; provide bus_id or assign a bus first'], 422);
            }
        }

        $departure = $v['departure_at'];
        $arrival = $v['arrival_at'] ?? null;

        // Check for schedule conflicts for the selected bus
        $existingSchedules = \App\Models\Schedule::where('bus_id', $busId)->get();

        foreach ($existingSchedules as $ex) {
            $exDep = $ex->departure_at;
            $exArr = $ex->arrival_at;

            // Normalize: if arrival is null treat as a point at departure
            if (!$exArr) $exArr = $exDep;
            $newArr = $arrival ?? $departure;

            // Overlap if exDep < newArr AND exArr > departure
            if (strtotime($exDep) < strtotime($newArr) && strtotime($exArr) > strtotime($departure)) {
                return response()->json(['message' => 'Schedule conflict: bus has another schedule overlapping this time'], 422);
            }
        }

        $payload = [
            'bus_id' => $busId,
            'route_id' => $routeId,
            'departure_at' => $departure,
            'arrival_at' => $arrival,
            'available_seats' => $v['available_seats'],
            'price' => $v['price'],
        ];

        return response()->json(Schedule::create($payload), 201);
    }

    public function update(Request $r, Schedule $schedule)
    { 
        $data = $r->validate([
            'bus_id'=>'nullable|exists:buses,id',
            'route_id'=>'nullable|exists:routes,id',
            'departure_at'=>'nullable|date',
            'arrival_at'=>'nullable|date',
            'available_seats'=>'nullable|integer',
            'price'=>'nullable|numeric'
        ]);

        $newBusId = $data['bus_id'] ?? $schedule->bus_id;
        $newDeparture = $data['departure_at'] ?? $schedule->departure_at;
        $newArrival = array_key_exists('arrival_at', $data) ? $data['arrival_at'] : $schedule->arrival_at;

        // Check conflicts against other schedules for the same bus
        $existingSchedules = \App\Models\Schedule::where('bus_id', $newBusId)->where('id', '!=', $schedule->id)->get();
        foreach ($existingSchedules as $ex) {
            $exDep = $ex->departure_at;
            $exArr = $ex->arrival_at ?: $exDep;
            $checkArr = $newArrival ?? $newDeparture;
            if (strtotime($exDep) < strtotime($checkArr) && strtotime($exArr) > strtotime($newDeparture)) {
                return response()->json(['message' => 'Schedule conflict: bus has another schedule overlapping this time'], 422);
            }
        }

        $schedule->update($data);
        return $schedule;
    }

    public function show(Schedule $schedule)
    { 
        return $schedule->load(['bus','route']); 
    }

    public function destroy(Schedule $schedule)
    { 
        $schedule->delete(); return response()->json(['message'=>'Deleted']); 
    }
}
