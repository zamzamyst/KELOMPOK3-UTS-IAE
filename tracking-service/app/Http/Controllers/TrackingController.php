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

        return response()->json($this->buildDetailedResponse($track, $busBase));
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

        // Get route data
        $routeData = null;
        if (isset($busData['route_id'])) {
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
        }

        return [
            'id' => $track->id,
            'bus_id' => $track->bus_id,
            'schedule_id' => $track->schedule_id,
            'location' => [
                'lat' => $track->lat,
                'lng' => $track->lng,
            ],
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
