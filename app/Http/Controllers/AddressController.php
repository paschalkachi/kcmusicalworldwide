<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    /**
     * Set an address as the default address for the user
     */
    public function setDefault(Request $request, $id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        
        // Use database transaction to ensure synchronization
        DB::beginTransaction();
        
        try {
            // Unset the current default address
            Address::where('user_id', Auth::id())
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            
            // Set the selected address as default
            $address->update(['is_default' => true]);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update default address',
                'error' => $e->getMessage()
            ], 500);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Default address updated successfully',
            'data' => [
                'id' => $address->id,
                'full_name' => $address->full_name,
                'phone' => $address->phone,
                'state' => $address->state->name,
                'lga' => $address->lga,
                'street' => $address->street,
                'description' => $address->description,
                'additional_info' => $address->additional_info,
            ]
        ]);
    }

    /**
     * Remove an address
     */
    public function destroy($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        
        // If deleting the default address, set another as default if available
        if ($address->is_default) {
            $otherAddresses = Address::where('user_id', Auth::id())
                                   ->where('id', '!=', $id)
                                   ->orderByDesc('is_default') // Prioritize existing default if available
                                   ->orderByDesc('updated_at')
                                   ->first();
                                   
            if ($otherAddresses) {
                $otherAddresses->update(['is_default' => true]);
            }
        }
        
        $address->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Address deleted successfully'
        ]);
    }
    
    /**
     * Update an address
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'street' => 'required|string|max:255',
            'lga' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'landmark' => 'nullable|string|max:255',
            'description' => 'required|string',
            'additional_info' => 'nullable|string',
        ]);

        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        $address->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Address updated successfully',
            'data' => $address
        ]);
    }
    
    /**
     * Show the form for editing an address
     */
    public function edit($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $address
        ]);
    }
}