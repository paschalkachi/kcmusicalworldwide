<?php

namespace App\Services;

use App\Models\Product;

class SkuGenerator
{
    /**
     * Generate a unique SKU for a product.
     */
    public static function generate(string $categoryCode): string
    {
        $brandPrefix = 'KMW';
        $year = now()->year;

        // Find the latest SKU for this category and year
        $latestProduct = Product::where('SKU', 'like', "$brandPrefix-$categoryCode-$year-%")
            ->orderBy('id', 'desc')
            ->first();

        // Determine next sequence number
        $nextNumber = 1;

        if ($latestProduct) {
            // Extract the last 4 digits from SKU
            $lastSkuParts = explode('-', $latestProduct->SKU);
            $lastNumber = (int) end($lastSkuParts);
            $nextNumber = $lastNumber + 1;
        }

        // Build the SKU with padded sequence
        $sku = sprintf("%s-%s-%s-%04d", $brandPrefix, $categoryCode, $year, $nextNumber);

        return $sku;
    }
}
