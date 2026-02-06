<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ShippingClass;

class FixShippingClassOverlaps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipping:fix-overlaps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fixes overlapping ranges in shipping classes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking for overlapping shipping class ranges...');

        // Fetch all shipping classes ordered by min_units
        $classes = ShippingClass::orderBy('min_units')->get();

        $prevMax = -1;
        $adjustments = [];

        foreach($classes as $class) {
            $this->info("Processing: {$class->name} (range {$class->min_units}-{$class->max_units})");
            
            if ($class->min_units <= $prevMax) {
                // There's an overlap with the previous class
                $this->warn("  -> OVERLAP DETECTED with previous range ending at {$prevMax}");
                
                // Adjust the current class to start after the previous one ends
                $newMin = $prevMax + 1;
                if ($newMin <= $class->max_units) {
                    $this->info("  -> Adjusting {$class->name} from {$class->min_units}-{$class->max_units} to {$newMin}-{$class->max_units}");
                    $class->update(['min_units' => $newMin]);
                    $adjustments[] = $class->name;
                } else {
                    // This shouldn't happen in normal cases, but just in case
                    $this->error("  -> ERROR: Adjusted range would be invalid ({$newMin} > {$class->max_units})");
                    $this->error("  -> This shipping class might need manual review");
                }
            }
            
            $prevMax = $class->max_units;
        }

        if (count($adjustments) > 0) {
            $this->info("\nAdjusted shipping classes: " . implode(', ', $adjustments));
        } else {
            $this->info("\nNo overlapping ranges found.");
        }

        $this->info("\nOverlap check completed.");
        
        return 0;
    }
}