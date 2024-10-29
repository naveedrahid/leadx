<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\UserPaymentLink;

class DeleteOldPaymentLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment-links:delete-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete payment links older than 10 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = Carbon::now()->subDays(10);
        UserPaymentLink::where('created_at', '<', $date)->delete();

        $this->info('Old payment links deleted successfully.');
    }
}
