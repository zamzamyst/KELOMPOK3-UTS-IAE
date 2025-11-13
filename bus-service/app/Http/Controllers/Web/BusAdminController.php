<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bus;

class BusAdminController extends Controller
{
    public function index()
    {
        $buses = Bus::all();
        return view('buses.index', compact('buses'));
    }

    public function create()
    {
        return view('buses.create');
    }

    public function store(Request $r)
    {
        $v = $r->validate([
            'plate_number' => 'required|unique:buses',
            'name' => 'required',
            'capacity' => 'required|integer',
            'type' => 'nullable',
            'route_id' => 'nullable|exists:routes,id'
        ]);

        $bus = Bus::create($v);
        return redirect()->route('buses.index')->with('success', 'Bus created');
    }

    public function show(Bus $bus)
    {
        return view('buses.show', compact('bus'));
    }

    public function edit(Bus $bus)
    {
        return view('buses.edit', compact('bus'));
    }

    public function update(Request $r, Bus $bus)
    {
        $v = $r->validate([
            'plate_number' => 'required|unique:buses,plate_number,'.$bus->id,
            'name' => 'required',
            'capacity' => 'required|integer',
            'type' => 'nullable',
            'route_id' => 'nullable|exists:routes,id'
        ]);

        $bus->update($v);
        return redirect()->route('buses.index')->with('success', 'Bus updated');
    }

    public function destroy(Bus $bus)
    {
        $bus->delete();
        return redirect()->route('buses.index')->with('success', 'Bus deleted');
    }
}
