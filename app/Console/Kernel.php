<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\CleanExpiredReservations;
use App\Console\Commands\RegenerateProductThumbnails;
use App\Console\Commands\FixShippingClassOverlaps;
use App\Console\Commands\CloseShippingClassGaps;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        CleanExpiredReservations::class,
        RegenerateProductThumbnails::class,
        FixShippingClassOverlaps::class,
        CloseShippingClassGaps::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Clean up expired reservations every 10 minutes
        $schedule->command('reservations:clean-expired')->everyTenMinutes();
        // Run gap closing command daily
        $schedule->command('shipping:close-gaps')->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}