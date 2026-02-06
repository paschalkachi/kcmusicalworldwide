<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ShippingClass;

echo "Checking for overlapping shipping class ranges...\n";

// Fetch all shipping classes ordered by min_units
$classes = ShippingClass::orderBy('min_units')->get();

$prevMax = -1;
$adjustments = [];

foreach($classes as $class) {
    echo "Processing: {$class->name} (range {$class->min_units}-{$class->max_units})\n";
    
    if ($class->min_units <= $prevMax) {
        // There's an overlap with the previous class
        echo "  -> OVERLAP DETECTED with previous range ending at {$prevMax}\n";
        
        // Adjust the current class to start after the previous one ends
        $newMin = $prevMax + 1;
        if ($newMin <= $class->max_units) {
            echo "  -> Adjusting {$class->name} from {$class->min_units}-{$class->max_units} to {$newMin}-{$class->max_units}\n";
            $class->update(['min_units' => $newMin]);
            $adjustments[] = $class->name;
        } else {
            // This shouldn't happen in normal cases, but just in case
            echo "  -> ERROR: Adjusted range would be invalid ({$newMin} > {$class->max_units})\n";
            echo "  -> This shipping class might need manual review\n";
        }
    }
    
    $prevMax = $class->max_units;
}

if (count($adjustments) > 0) {
    echo "\nAdjusted shipping classes: " . implode(', ', $adjustments) . "\n";
} else {
    echo "\nNo overlapping ranges found.\n";
}

echo "\nOverlap check completed.\n";