<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\HapusSuratKedaluwarsa::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Sistem hapus otomatis dinonaktifkan
    }


    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
