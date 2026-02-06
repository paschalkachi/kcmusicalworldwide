<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get a single product's details
     */
    public function show($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'stock_status' => $product->stock_status,
            'quantity' => $product->quantity,
            'preorder_limit' => $product->preorder_limit,
            'available_quantity' => $product->getAvailableQuantityAttribute(),
        ]);
    }
}