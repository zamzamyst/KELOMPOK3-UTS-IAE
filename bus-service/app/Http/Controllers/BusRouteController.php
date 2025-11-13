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

        $route->buses()->attach($request->bus_id);

        return $route->load('buses');
    }
}
