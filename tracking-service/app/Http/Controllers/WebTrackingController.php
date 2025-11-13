<?php

namespace App\Http\Controllers;

use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WebTrackingController extends Controller
{
    public function index(Request $request)
    {
        $trackings = Tracking::latest()->paginate(20);
        return view('trackings.index', compact('trackings'));
    }

    public function create()
    {
        return view('trackings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|integer',
            'schedule_id' => 'nullable|integer',
        ]);

        // Panggil API TrackingService lewat Gateway (bukan langsung DB)
        $apiGateway = rtrim(env('API_GATEWAY_URL', 'http://127.0.0.1:4000'), '/');
        $endpoint = "{$apiGateway}/api/tracking-service/trackings";

        $payload = [
            'bus_id' => (int) $request->bus_id,
        ];

        if ($request->filled('schedule_id')) {
            $payload['schedule_id'] = (int) $request->schedule_id;
        }

        $response = Http::post($endpoint, $payload);

        if ($response->successful()) {
            return redirect()->route('trackings.index')->with('success', 'Tracking created successfully!');
        }

        return back()->withErrors([
            'error' => 'Failed to create tracking. ' . ($response->json('message') ?? 'Unknown error'),
        ]);
    }

    public function show($id)
    {
        return view('trackings.show', ['id' => $id]);
    }

    public function destroy($id)
    {
        $apiGateway = rtrim(env('API_GATEWAY_URL', 'http://127.0.0.1:4000'), '/');
        $endpoint = "{$apiGateway}/api/tracking-service/trackings/{$id}";

        $response = Http::delete($endpoint);

        if ($response->successful()) {
            return redirect()->route('trackings.index')->with('success', 'Tracking deleted successfully!');
        }

        return back()->withErrors([
            'error' => 'Failed to delete tracking. ' . ($response->json('message') ?? 'Unknown error'),
        ]);
    }
}
