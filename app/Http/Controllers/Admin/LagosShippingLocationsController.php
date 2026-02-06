<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LagosLocation;
use Illuminate\Http\Request;

class LagosShippingLocationsController extends Controller {
    public function index()
    {
        $locations = LagosLocation::latest()->paginate(10);
        return view('admin.shipping.lagos-locations.index', compact('locations'));
    }

    public function create()
    {
        return view('admin.shipping.lagos-locations.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'shipping_price' => 'required|numeric'
        ]);

        LagosLocation::create($request->all());

        return redirect()->route('lagos-locations.index')->with('success', 'Lagos location created successfully');
    }

    public function edit(LagosLocation $lagosLocation)
    {
        return view('admin.shipping.lagos-locations.add', compact('lagosLocation'));
    }

    public function update(Request $request, LagosLocation $lagosLocation)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'shipping_price' => 'required|numeric'
        ]);

        $lagosLocation->update($request->all());

        return redirect()->route('lagos-locations.index')->with('success', 'Lagos location updated successfully');
    }

    public function destroy(LagosLocation $lagosLocation)
    {
        $lagosLocation->delete();

        return redirect()->route('lagos-locations.index')->with('status', 'Lagos location deleted successfully');
    }
}