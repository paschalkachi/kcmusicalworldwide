<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ShippingClass;

class CloseShippingClassGaps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipping:close-gaps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Closes any gaps between shipping class ranges';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking for gaps in shipping class ranges...');
        
        $classes = ShippingClass::orderBy('min_units')->get();
        $initialCount = $classes->count();
        
        if ($initialCount === 0) {
            $this->info("No shipping classes found.");
            return 0;
        }
        
        ShippingClass::closeGaps();
        
        $this->info("Gaps between shipping classes have been closed.");
        
        return 0;
    }
}