<?php

namespace App\Http\Controllers;

use App\Models\LagosLocation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LagosLocationController extends Controller
{
    /**
     * Return all Lagos locations as JSON
     */
    public function index(): JsonResponse
    {
        try {
            // First, check if the LagosLocation model exists and has data
            $locations = LagosLocation::select('id', 'name', 'shipping_price')->get();
            
            // Log for debugging purposes
            \Log::info('Lagos locations fetched', ['count' => $locations->count()]);
            
            return response()->json($locations);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error fetching Lagos locations: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return an empty array as fallback with a 500 status
            return response()->json(['error' => 'Unable to fetch locations'], 500);
        }
    }
}