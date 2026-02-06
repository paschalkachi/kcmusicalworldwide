<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index()
    {
        $states = State::latest()->paginate(10);
        return view('admin.shipping.states.index', compact('states'));
    }

    public function create()
    {
        return view('admin.shipping.states.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:states,name',
            'base_shipping_price' => 'required|numeric|min:0',
            'is_lagos' => 'nullable|boolean'
        ]);

        State::create($request->only(['name', 'base_shipping_price', 'is_lagos']));

        return redirect()->route('states.index')->with('success', 'State created successfully');
    }

    public function edit(State $state)
    {
        return view('admin.shipping.states.add', compact('state'));
    }

    public function update(Request $request, State $state)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:states,name,'.$state->id,
            'base_shipping_price' => 'required|numeric|min:0',
            'is_lagos' => 'nullable|boolean'
        ]);

        $state->update($request->only(['name', 'base_shipping_price', 'is_lagos']));

        return redirect()->route('states.index')->with('status', 'State updated successfully');
    }

    public function destroy(State $state)
    {
        $state->delete();

        return redirect()->route('states.index')->with('status', 'State deleted successfully');
    }
}
