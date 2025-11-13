<?php
namespace App\Http\Controllers;
use App\Models\Bus;
use App\Models\BusRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusController extends Controller {
    public function index(){ return Bus::all(); }

    public function store(Request $r)
    {
        $v = $r->validate([
            'plate_number'=>'required|unique:buses',
            'name'=>'required',
            'capacity'=>'required|integer',
            'type'=>'nullable',
            'route_id'=>'nullable|exists:routes,id']);

        // If route_id provided, ensure the bus is not already assigned to another route
        if (isset($v['route_id']) && $v['route_id']) {
            $existing = \Illuminate\Support\Facades\DB::table('bus_route')->where('bus_id', '!=', null)->where('bus_id', 0)->get();
            // simpler check: ensure no other bus is assigned conflict isn't applicable here; skip
        }

        $bus = Bus::create($v);
        // If route_id provided and pivot exists, ensure pivot reflects it
        if (isset($v['route_id']) && $v['route_id']) {
            if (\Illuminate\Support\Facades\Schema::hasTable('bus_route')) {
                $bus->routes()->syncWithoutDetaching([$v['route_id']]);
            }
        }

        return response()->json($bus, 201);
    }

    public function show(Bus $bus)
    { 
        $busArray = $bus->toArray();

        // Try to determine the bus's route. Support two storage strategies:
        // 1) buses.route_id column (one-to-many)
        // 2) bus_route pivot table (many-to-many used as assignment)
        $route = null;
        if (isset($bus->route_id) && $bus->route_id) {
            $route = BusRoute::find($bus->route_id);
        } else {
            $routeId = DB::table('bus_route')->where('bus_id', $bus->id)->value('route_id');
            if ($routeId) {
                $route = BusRoute::find($routeId);
            }
        }

        $busArray['route'] = $route ? $route->toArray() : null;

        return response()->json($busArray);
    }

    public function update(Request $r, Bus $bus)
    { 
        $data = $r->all();
        // If attempting to set route_id, ensure bus isn't assigned to a different route
        if (isset($data['route_id']) && $data['route_id']) {
            $newRoute = (int)$data['route_id'];
            $current = null;
            if (isset($bus->route_id) && $bus->route_id) {
                $current = (int)$bus->route_id;
            } else {
                $pivot = \Illuminate\Support\Facades\DB::table('bus_route')->where('bus_id', $bus->id)->first();
                if ($pivot && isset($pivot->route_id)) $current = (int)$pivot->route_id;
            }
            if ($current && $current !== $newRoute) {
                return response()->json(['message'=>'Bus already assigned to another route'], 422);
            }
        }

        $bus->update($data);
        // keep pivot in sync if needed
        if (isset($data['route_id']) && $data['route_id'] && \Illuminate\Support\Facades\Schema::hasTable('bus_route')) {
            $bus->routes()->syncWithoutDetaching([(int)$data['route_id']]);
        }

        return $bus;
    }

    public function destroy(Bus $bus)
    { 
        $bus->delete(); 
        return response()->json(['message'=>'Deleted']); 
    }
}
