<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\BuyIngredientsCommand;
use App\Console\Commands\ProcessOrdersCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(BuyIngredientsCommand::class)
            ->everyMinute()
            ->withoutOverlapping()
            ->then(function (): void {
                $this->call(ProcessOrdersCommand::class);
            })
        ;
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
