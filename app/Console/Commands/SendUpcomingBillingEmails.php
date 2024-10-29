<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Jobs\UpcomingBillingReminderJob;

class SendUpcomingBillingEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:upcoming-billing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to customers with upcoming subscription billing';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $subscriptions = Subscription::whereHas('user', function($query) {
            $query->whereHas('customer_details', function($subQuery) {
                $subQuery->where('auto_renewal_subscription', 1);
            });
        })->where('status', ['active', 'trialing'])->whereBetween('next_billing_date', [now(), now()->addDays(7)])->get();

        if($subscriptions->count()) {
            foreach($subscriptions as $subscription) {
                $customer = $subscription->user;
                dispatch(new UpcomingBillingReminderJob($customer->id, $subscription->id));
            }
        }

        $this->info('Emails sent to customers with upcoming billing.');
        return Command::SUCCESS;
    }
}
