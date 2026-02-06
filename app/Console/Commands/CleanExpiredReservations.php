<?php

namespace App\Console\Commands;

use App\Services\InventoryService;
use Illuminate\Console\Command;

class CleanExpiredReservations extends Command
{
    protected $signature = 'reservations:clean-expired';
    protected $description = 'Clean up expired inventory reservations';

    public function handle(InventoryService $inventoryService)
    {
        $this->info('Cleaning up expired reservations...');
        
        $inventoryService->releaseExpiredReservations();
        
        $this->info('Expired reservations cleaned successfully.');
    }
}