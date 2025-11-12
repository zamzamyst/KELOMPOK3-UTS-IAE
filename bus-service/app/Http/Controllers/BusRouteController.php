<?php
namespace App\Http\Controllers;
use App\Models\BusRoute;
use Illuminate\Http\Request;

class BusRouteController extends Controller {
    public function index(){ return BusRoute::all(); }
    public function store(Request $r){
        $v = $r->validate(['code'=>'required|unique:routes','origin'=>'required','destination'=>'required','stops'=>'nullable|array']);
        if (isset($v['stops'])) $v['stops'] = json_encode($v['stops']);
        return response()->json(BusRoute::create($v), 201);
    }
    public function show(BusRoute $route){ return $route; }
    public function update(Request $r, BusRoute $route){ $route->update($r->all()); return $route; }
    public function destroy(BusRoute $route){ $route->delete(); return response()->json(['message'=>'Deleted']); }
}
