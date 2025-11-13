<?php
namespace App\Http\Controllers;
use App\Models\BusRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusRouteController extends Controller {
    public function index()
    { 
        return BusRoute::all(); 
    }

    public function store(Request $r)
    {
        $v = $r->validate([
            'code'=>'required|unique:routes',
            'origin'=>'required',
            'destination'=>'required',
            'stops'=>'nullable|array'
        ]);
        // don't json_encode — BusRoute model casts 'stops' to array and
        // Eloquent will serialize it for the JSON column.
        return response()->json(BusRoute::create($v), 201);
    }

    public function show(BusRoute $route)
    { 
        return $route; 
    }

    public function update(Request $r, BusRoute $route)
    {
        $validator = Validator::make($r->all(), [
            'code'=>'sometimes|required|unique:routes,code,'.$route->id,
            'origin'=>'sometimes|required',
            'destination'=>'sometimes|required',
            'stops'=>'nullable|array'
        ]);

        $validator->before(function ($validator) {
            $data = $validator->getData();
            if (isset($data['stops']) && is_string($data['stops'])) {
                $decoded = json_decode($data['stops'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data['stops'] = $decoded;
                    $validator->setData($data);
                }
            }
        });

        $v = $validator->validate();

        $route->update($v);
        return $route;
    }

    public function destroy(BusRoute $route)
    { 
        $route->delete(); return response()->json(['message'=>'Deleted']); 
    }

    public function assignBus(Request $request, BusRoute $route)
    {
        $request->validate([
            'bus_id' => 'required|exists:buses,id',
        ]);

        $busId = (int)$request->bus_id;

        // Determine current assignment for the bus (supports both route_id column and pivot table)
        $currentRouteId = null;
        if (isset($request->route_id)) {
            // not used here
        }

        // Check route_id column first
        try {
            $bus = \App\Models\Bus::find($busId);
            if ($bus && isset($bus->route_id) && $bus->route_id) {
                $currentRouteId = (int)$bus->route_id;
            }
        } catch (\Throwable $e) {
            // ignore
        }

        // If not found via column, check pivot
        if (!$currentRouteId) {
            $pivot = \Illuminate\Support\Facades\DB::table('bus_route')->where('bus_id', $busId)->first();
            if ($pivot && isset($pivot->route_id)) {
                $currentRouteId = (int)$pivot->route_id;
            }
        }

        // If already assigned to different route, reject
        if ($currentRouteId && $currentRouteId !== (int)$route->id) {
            return response()->json(['message' => 'Bus is already assigned to another route'], 422);
        }

        // If already assigned to same route, return current state
        if ($currentRouteId === (int)$route->id) {
            return $route->load('buses');
        }

        // Assign: prefer pivot if exists, otherwise set route_id column
        $hasPivot = \Illuminate\Support\Facades\Schema::hasTable('bus_route');
        if ($hasPivot) {
            $route->buses()->attach($busId);
        } else {
            if ($bus) {
                $bus->route_id = $route->id;
                $bus->save();
            }
        }

        return $route->load('buses');
    }
}
