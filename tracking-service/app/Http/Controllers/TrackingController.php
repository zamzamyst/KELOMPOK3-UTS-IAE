<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Tracking;
use Illuminate\Support\Facades\Http;

class TrackingController extends Controller {
    public function index() {
    $trackings = Tracking::all();

    return $trackings->map(function ($track) {
        $busBase = env('BUS_SERVICE_URL','http://127.0.0.1:8001');
        $resp = Http::get("{$busBase}/api/buses/{$track->bus_id}");

        $busData = $resp->ok() ? $resp->json() : null;

        // Jika hasilnya array (misal [ {…} ])
        if (is_array($busData) && isset($busData[0])) {
            $busData = $busData[0];
        }

        return [
            'id' => $track->id,
            'bus_id' => $track->bus_id,
            'bus_name' => $busData['bus_name'] ?? null,
            'lat' => $track->lat,
            'lng' => $track->lng,
        ];
    });
}

    public function store(Request $r){
        $v = $r->validate(['bus_id'=>'required|integer','lat'=>'required','lng'=>'required']);
        return response()->json(Tracking::create($v), 201);
    }

    public function show($id) {
        return response()->json(Bus::findOrFail($id));
    }
}