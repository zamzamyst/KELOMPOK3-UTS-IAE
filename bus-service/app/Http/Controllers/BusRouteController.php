<?php
namespace App\Http\Controllers;
use App\Models\BusRoute;
use Illuminate\Http\Request;

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
        $v = $r->validate([
            'code'=>'sometimes|required|unique:routes,code,'.$route->id,
            'origin'=>'sometimes|required',
            'destination'=>'sometimes|required',
            'stops'=>'nullable|array'
        ]);

        // If client sends stops as JSON string, try to decode it safely
        if (isset($v['stops']) && is_string($v['stops'])) {
            $decoded = json_decode($v['stops'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $v['stops'] = $decoded;
            }
        }

        $route->update($v);
        return $route;
    }

    public function destroy(BusRoute $route)
    { 
        $route->delete(); return response()->json(['message'=>'Deleted']); 
    }
}
