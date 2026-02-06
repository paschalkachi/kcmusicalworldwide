<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingClass extends Model
{
    protected $fillable = [
        'name',
        'min_units',
        'max_units',
        'load_factor',
    ];

    /**
     * A shipping class has many products
     */
    public function products()
    {
        return $this->hasMany(Product::class,'shipping_class_id');
    }

    /**
     * A shipping class has many orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    /**
     * Check if the given range overlaps with any existing shipping class ranges
     */
    public static function hasOverlappingRange($min, $max, $excludeId = null)
    {
        $query = self::where(function($q) use ($min, $max) {
            // Check if the new range overlaps with an existing range
            $q->where('min_units', '<=', $max)
              ->where('max_units', '>=', $min);
        });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
    
    /**
     * Get overlapping ranges with the given range
     */
    public static function getOverlappingRanges($min, $max, $excludeId = null)
    {
        $query = self::where(function($q) use ($min, $max) {
            // Check if the new range overlaps with an existing range
            $q->where('min_units', '<=', $max)
              ->where('max_units', '>=', $min);
        });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->get();
    }
    
    /**
     * Resolve overlapping ranges by adjusting existing classes
     */
    public static function resolveOverlaps($newMin, $newMax, $currentId = null)
    {
        $overlaps = self::getOverlappingRanges($newMin, $newMax, $currentId);
        
        foreach ($overlaps as $overlap) {
            if ($newMin <= $overlap->max_units && $newMax >= $overlap->min_units) {
                // Determine how to adjust the overlapping range
                if ($newMin <= $overlap->min_units && $newMax >= $overlap->max_units) {
                    // New range completely encompasses the old range, delete it
                    $overlap->delete();
                } elseif ($newMin <= $overlap->min_units) {
                    // New range overlaps from the left, adjust the start of existing range
                    $overlap->update(['min_units' => $newMax + 1]);
                } elseif ($newMax >= $overlap->max_units) {
                    // New range overlaps from the right, adjust the end of existing range
                    $overlap->update(['max_units' => $newMin - 1]);
                } else {
                    // New range splits the existing range, need to handle this case
                    $newRangeStart = $newMax + 1;
                    $newRangeEnd = $overlap->max_units;
                    
                    // Update the existing range to end before the new range
                    $overlap->update(['max_units' => $newMin - 1]);
                    
                    // Create a new class for the remaining part of the original range
                    $overlapClone = $overlap->replicate();
                    $overlapClone->min_units = $newRangeStart;
                    $overlapClone->max_units = $newRangeEnd;
                    $overlapClone->name = $overlap->name . ' (Split)';
                    $overlapClone->save();
                }
            }
        }
    }
    
    /**
     * Close any gaps between shipping class ranges
     */
    public static function closeGaps()
    {
        $classes = self::orderBy('min_units')->get();
        
        foreach ($classes as $index => $class) {
            if ($index > 0) {
                $prevClass = $classes[$index - 1];
                
                // Check if there's a gap between previous class and current class
                if ($prevClass->max_units + 1 < $class->min_units) {
                    $gapStart = $prevClass->max_units + 1;
                    $gapEnd = $class->min_units - 1;
                    
                    // Calculate mid point of the gap
                    $midPoint = intval(($gapStart + $gapEnd) / 2);
                    
                    // Adjust the previous class's max_units to mid point
                    $prevClass->update(['max_units' => $midPoint]);
                    
                    // Adjust the current class's min_units to mid point + 1
                    $class->update(['min_units' => $midPoint + 1]);
                }
            }
        }
    }
}