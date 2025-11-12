<?php
namespace App\Http\Controllers;
use App\Models\Bus;
use Illuminate\Http\Request;

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

        return response()->json(Bus::create($v), 201);
    }

    public function show(Bus $bus)
    { 
        return $bus; 
    }

    public function update(Request $r, Bus $bus)
    { 
        $bus->update($r->all()); return $bus; 
    }

    public function destroy(Bus $bus)
    { 
        $bus->delete(); 
        return response()->json(['message'=>'Deleted']); 
    }
}
