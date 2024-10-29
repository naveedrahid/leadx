<?php

namespace App\Console;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\SendUpcomingBillingEmails::class,
        Commands\SendSubscriptionExpiryReminders::class,
        Commands\ExpireSubscriptions::class,
        Commands\DeleteOldFiles::class,
        Commands\DeleteOldPaymentLinks::class
    ];
    
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('queue:listen')->everyMinute()->withoutOverlapping();
        $schedule->command('queue:retry all')->everyFiveMinutes();
        $schedule->command('email:upcoming-billing')->daily();
        $schedule->command('email:subscription-expiry-reminders')->daily();
        $schedule->command('subscriptions:expire')->daily();
        $schedule->command('payment-links:delete-old')->daily();
        $schedule->command('storage:cleanup /public/leads_export/ 1')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
