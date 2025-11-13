<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tracking;
use Illuminate\Support\Facades\Http;

class TrackingController extends Controller
{
    public function index()
    {
        $busBase = env('BUS_SERVICE_URL', 'http://127.0.0.1:8001');

        return Tracking::all()->map(function ($track) use ($busBase) {
            return $this->buildDetailedResponse($track, $busBase);
        });
    }

    public function destroy($id)
    {
        $track = Tracking::findOrFail($id);
        $track->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function store(Request $r)
    {
        $v = $r->validate([
            'bus_id' => 'required|integer',
        ]);

        // generate random coordinates near a sensible default (Bandung area)
        $centerLat = -6.914744;
        $centerLng = 107.609810;
        $radius = 0.05; // ~5km-ish radius in degrees (approx)

        $lat = $this->randomCoordinate($centerLat, $radius);
        $lng = $this->randomCoordinate($centerLng, $radius);

        // If a tracking for this bus already exists, update its location instead of creating a new record
        $existing = Tracking::where('bus_id', $v['bus_id'])->latest()->first();

        if ($existing) {
            $existing->lat = $lat;
            $existing->lng = $lng;
            // optionally update schedule_id if provided in request
            if ($r->has('schedule_id')) {
                $existing->schedule_id = $r->input('schedule_id');
            }
            $existing->save();
            $existing->refresh();

            $busBase = env('BUS_SERVICE_URL', 'http://127.0.0.1:8001');
            return response()->json($this->buildDetailedResponse($existing, $busBase), 200);
        }

        $track = Tracking::create([
            'bus_id' => $v['bus_id'],
            'lat' => $lat,
            'lng' => $lng,
            'schedule_id' => $r->input('schedule_id') ?? null,
        ]);

        $track->refresh();

        $busBase = env('BUS_SERVICE_URL', 'http://127.0.0.1:8001');

        return response()->json($this->buildDetailedResponse($track, $busBase), 201);
    }

    public function show($id)
    {
        $track = Tracking::findOrFail($id);
        $busBase = env('BUS_SERVICE_URL', 'http://127.0.0.1:8001');

        $detailed = $this->buildDetailedResponse($track, $busBase);
        return response()->json($detailed);
    }

    /**
     * Build the detailed response for a tracking entry, including bus, route and schedule.
     */
    private function buildDetailedResponse(Tracking $track, string $busBase)
    {
        // Get bus data with detailed info
        $busResp = Http::get("{$busBase}/api/buses/{$track->bus_id}");
        $busData = $busResp->ok() ? $busResp->json() : null;

        if (is_array($busData) && isset($busData[0])) {
            $busData = $busData[0];
        }

        // Get route data. Prefer nested 'route' returned by bus-service (/api/buses/{id}),
        // otherwise fall back to fetching /api/routes/{id} when only route_id is available.
        $routeData = null;
        if (isset($busData['route']) && is_array($busData['route'])) {
            $routeData = $busData['route'];
        } elseif (isset($busData['route_id']) && $busData['route_id']) {
            $routeResp = Http::get("{$busBase}/api/routes/{$busData['route_id']}");
            $routeData = $routeResp->ok() ? $routeResp->json() : null;
            if (is_array($routeData) && isset($routeData[0])) {
                $routeData = $routeData[0];
            }
        }

        // Get schedule data (schedules are in bus-service, not ticket-service)
        $scheduleData = null;
        if ($track->schedule_id) {
            $scheduleResp = Http::get("{$busBase}/api/schedules/{$track->schedule_id}");
            $scheduleData = $scheduleResp->ok() ? $scheduleResp->json() : null;
            if (is_array($scheduleData) && isset($scheduleData[0])) {
                $scheduleData = $scheduleData[0];
            }
        } else {
            // No explicit schedule_id: try to find the bus's next schedule
            $schedulesResp = Http::get("{$busBase}/api/schedules");
            if ($schedulesResp->ok()) {
                $schedules = $schedulesResp->json();
                if (is_array($schedules)) {
                    $candidates = [];
                    foreach ($schedules as $s) {
                        if (is_array($s) && isset($s['bus_id']) && $s['bus_id'] == $track->bus_id) {
                            $candidates[] = $s;
                        }
                    }

                    $now = date('Y-m-d H:i:s');
                    $next = null;
                    // prefer next upcoming schedule
                    foreach ($candidates as $c) {
                        if (!isset($c['departure_at'])) continue;
                        if (strtotime($c['departure_at']) >= strtotime($now)) {
                            if ($next === null || strtotime($c['departure_at']) < strtotime($next['departure_at'])) {
                                $next = $c;
                            }
                        }
                    }
                    // if none upcoming, pick the most recent past schedule
                    if ($next === null) {
                        foreach ($candidates as $c) {
                            if (!isset($c['departure_at'])) continue;
                            if ($next === null || strtotime($c['departure_at']) > strtotime($next['departure_at'])) {
                                $next = $c;
                            }
                        }
                    }

                    if ($next) {
                        $scheduleData = $next;
                    }
                }
            }
        }

        return [
            'id' => $track->id,
            'lat' => $track->lat,
            'lng' => $track->lng,
            'bus' => [
                'id' => $busData['id'] ?? null,
                'name' => $busData['name'] ?? null,
                'plate_number' => $busData['plate_number'] ?? null,
                'capacity' => $busData['capacity'] ?? null,
            ],
            'route' => $routeData ? [
                'id' => $routeData['id'] ?? null,
                'code' => $routeData['code'] ?? null,
                'origin' => $routeData['origin'] ?? null,
                'destination' => $routeData['destination'] ?? null,
                'stops' => $routeData['stops'] ?? null,
            ] : null,
            'schedule' => $scheduleData ? [
                'id' => $scheduleData['id'] ?? null,
                'departure_at' => $scheduleData['departure_at'] ?? null,
                'arrival_at' => $scheduleData['arrival_at'] ?? null,
                'available_seats' => $scheduleData['available_seats'] ?? null,
                'price' => $scheduleData['price'] ?? null,
            ] : null,
            'created_at' => $track->created_at,
        ];
    }

    /**
     * Generate a random coordinate around a center point.
     */
    private function randomCoordinate(float $center, float $radiusDegrees = 0.05): float
    {
        $rand = mt_rand() / mt_getrandmax();
        $offset = ($rand * 2 * $radiusDegrees) - $radiusDegrees;
        return $center + $offset;
    }
}
