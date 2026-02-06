<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingClass;
use Illuminate\Http\Request;

class ShippingClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shippingClasses = ShippingClass::orderBy('min_units')->paginate(10);
        
        return view('admin.shipping.shipping-classes.index', compact('shippingClasses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $existingClasses = ShippingClass::orderBy('min_units', 'asc')->get();
        return view('admin.shipping.shipping-classes.create', compact('existingClasses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shipping_classes,name',
            'min_units' => 'required|integer|min:0',
            'max_units' => 'required|integer|min:0|gte:min_units',
            'load_factor' => 'required|numeric|min:0'
        ]);

        // Check for overlapping ranges
        $hasOverlap = ShippingClass::hasOverlappingRange($validated['min_units'], $validated['max_units']);
        
        if ($hasOverlap) {
            // Automatically resolve overlaps
            ShippingClass::resolveOverlaps($validated['min_units'], $validated['max_units']);
        }

        // Check if the load factor is less than the preceding class
        $precedingClass = ShippingClass::where('max_units', '<', $validated['min_units'])
                                       ->orderBy('max_units', 'desc')
                                       ->first();
        
        if ($precedingClass && $validated['load_factor'] < $precedingClass->load_factor) {
            return redirect()->back()
                ->withErrors(['load_factor_error' => "Load factor ({$validated['load_factor']}) cannot be less than the preceding class ({$precedingClass->name}) load factor ({$precedingClass->load_factor})."])
                ->withInput();
        }

        $shippingClass = ShippingClass::create($validated);

        // Close any gaps that may have formed
        ShippingClass::closeGaps();

        return redirect()->route('shipping-classes.index')
                         ->with('success', 'Shipping class created successfully.' . ($hasOverlap ? ' Overlapping ranges have been adjusted.' : ''));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShippingClass $shippingClass)
    {
        $existingClasses = ShippingClass::orderBy('min_units', 'asc')->where('id', '!=', $shippingClass->id)->get();
        return view('admin.shipping.shipping-classes.edit', compact('shippingClass', 'existingClasses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShippingClass $shippingClass)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shipping_classes,name,'.$shippingClass->id,
            'min_units' => 'required|integer|min:0',
            'max_units' => 'required|integer|min:0|gte:min_units',
            'load_factor' => 'required|numeric|min:0'
        ]);

        // Store original values to detect changes
        $originalMin = $shippingClass->min_units;
        $originalMax = $shippingClass->max_units;

        // Check for overlapping ranges (excluding current record)
        $hasOverlap = ShippingClass::hasOverlappingRange($validated['min_units'], $validated['max_units'], $shippingClass->id);
        
        if ($hasOverlap) {
            // Automatically resolve overlaps
            ShippingClass::resolveOverlaps($validated['min_units'], $validated['max_units'], $shippingClass->id);
        }

        // Check if the load factor is less than the preceding class
        $precedingClass = ShippingClass::where('max_units', '<', $validated['min_units'])
                                       ->where('id', '!=', $shippingClass->id)
                                       ->orderBy('max_units', 'desc')
                                       ->first();
        
        if ($precedingClass && $validated['load_factor'] < $precedingClass->load_factor) {
            return redirect()->back()
                ->withErrors(['load_factor_error' => "Load factor ({$validated['load_factor']}) cannot be less than the preceding class ({$precedingClass->name}) load factor ({$precedingClass->load_factor})."])
                ->withInput();
        }

        $shippingClass->update($validated);

        // After update, check if this change created any gaps or overlaps that need fixing
        // But make sure the updated class itself keeps its values
        $this->fixAdjacentRanges($shippingClass, $originalMin, $originalMax);

        return redirect()->route('shipping-classes.index')
                         ->with('success', 'Shipping class updated successfully.' . ($hasOverlap ? ' Overlapping ranges have been adjusted.' : ''));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingClass $shippingClass)
    {
        // Get the previous and next shipping classes to handle potential gaps
        $previousClass = ShippingClass::where('max_units', '<', $shippingClass->min_units)
                                      ->orderBy('max_units', 'desc')
                                      ->first();
                                      
        $nextClass = ShippingClass::where('min_units', '>', $shippingClass->max_units)
                                  ->orderBy('min_units', 'asc')
                                  ->first();

        // Delete the current shipping class
        $shippingClass->delete();

        // If there's a gap between previous and next class, close it
        if ($previousClass && $nextClass) {
            $gapStart = $previousClass->max_units + 1;
            $gapEnd = $nextClass->min_units - 1;
            
            if ($gapStart <= $gapEnd) {
                // Calculate mid point of the gap
                $midPoint = intval(($gapStart + $gapEnd) / 2);
                
                // Adjust the previous class's max_units to mid point
                $previousClass->update(['max_units' => $midPoint]);
                
                // Adjust the next class's min_units to mid point + 1
                $nextClass->update(['min_units' => $midPoint + 1]);
            }
        }

        // Close any additional gaps that may have formed
        ShippingClass::closeGaps();

        return redirect()->route('shipping-classes.index')
                         ->with('success', 'Shipping class deleted successfully.');
    }
    
    /**
     * Close any existing gaps between shipping classes
     */
    public function closeGaps()
    {
        ShippingClass::closeGaps();

        return redirect()->route('shipping-classes.index')
                         ->with('success', 'Gaps between shipping classes have been closed.');
    }
    
    /**
     * Fix adjacent ranges when a shipping class is updated to prevent gaps/overlaps
     */
    private function fixAdjacentRanges($updatedClass, $originalMin, $originalMax)
    {
        // Find classes that come immediately before and after the updated class
        $prevClass = ShippingClass::where('max_units', '<', $updatedClass->min_units)
                                  ->orderBy('max_units', 'desc')
                                  ->first();
        
        $nextClass = ShippingClass::where('min_units', '>', $updatedClass->max_units)
                                  ->orderBy('min_units', 'asc')
                                  ->first();
        
        // Fix gap with previous class if one exists
        if ($prevClass && $prevClass->max_units + 1 < $updatedClass->min_units) {
            // If we expanded the updated class backwards, reduce prev class
            if ($updatedClass->min_units < $originalMin) {
                $prevClass->update(['max_units' => $updatedClass->min_units - 1]);
            } else {
                // Otherwise, expand prev class to meet the updated class
                $prevClass->update(['max_units' => $updatedClass->min_units - 1]);
            }
        }
        
        // Fix gap with next class if one exists
        if ($nextClass && $updatedClass->max_units + 1 < $nextClass->min_units) {
            // If we expanded the updated class forwards, reduce next class
            if ($updatedClass->max_units > $originalMax) {
                $nextClass->update(['min_units' => $updatedClass->max_units + 1]);
            } else {
                // Otherwise, contract next class to start right after updated class
                $nextClass->update(['min_units' => $updatedClass->max_units + 1]);
            }
        }
        
        // Handle overlaps with next class if one exists
        if ($nextClass && $updatedClass->max_units >= $nextClass->min_units) {
            // Reduce next class's min_units to be after the updated class
            $nextClass->update(['min_units' => $updatedClass->max_units + 1]);
        }
    }
}