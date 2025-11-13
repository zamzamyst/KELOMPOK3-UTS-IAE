<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BusRoute;
use Illuminate\Http\Request;

class RouteAdminController extends Controller
{
    public function index()
    {
        $routes = BusRoute::all();
        return view('route.index', compact('routes'));
    }

    public function create()
    {
        return view('route.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:routes',
            'origin' => 'required',
            'destination' => 'required',
            'stops' => 'nullable|string'
        ]);

        // Convert comma-separated stops string to array
        if ($validated['stops']) {
            $validated['stops'] = array_map('trim', explode(',', $validated['stops']));
            $validated['stops'] = array_filter($validated['stops']);
        }

        $route = BusRoute::create($validated);
        return redirect()->route('routes.index')->with('success', 'Route created successfully');
    }

    public function show(BusRoute $route)
    {
        return view('route.show', compact('route'));
    }

    public function edit(BusRoute $route)
    {
        return view('route.edit', compact('route'));
    }

    public function update(Request $request, BusRoute $route)
    {
        $validated = $request->validate([
            'code' => 'required|unique:routes,code,'.$route->id,
            'origin' => 'required',
            'destination' => 'required',
            'stops' => 'nullable|string'
        ]);

        // Convert comma-separated stops string to array
        if ($validated['stops']) {
            $validated['stops'] = array_map('trim', explode(',', $validated['stops']));
            $validated['stops'] = array_filter($validated['stops']);
        }

        $route->update($validated);
        return redirect()->route('routes.index')->with('success', 'Route updated successfully');
    }

    public function destroy(BusRoute $route)
    {
        $route->delete();
        return redirect()->route('routes.index')->with('success', 'Route deleted successfully');
    }
}
