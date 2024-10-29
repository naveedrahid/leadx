<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Jobs\SubscriptionExpiryReminderMailJob;

class SendSubscriptionExpiryReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:subscription-expiry-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails to customers whose subscriptions are about to expire';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $subscriptions = Subscription::whereHas('package', function($query) {
            $query->where('regular_price', '!=', 0);
        })->whereHas('user', function($query) {
            $query->whereHas('customer_details', function($subQuery) {
                $subQuery->where('auto_renewal_subscription', 0);
            });
        })->status(['active', 'trialing'])->whereBetween('ended_at', [now(), now()->addDays(7)])->get();

        if($subscriptions->count()) {
            foreach($subscriptions as $subscription) {
                $customer = $subscription->user;
                dispatch(new SubscriptionExpiryReminderMailJob($customer->id, $subscription->id));
            }
        }

        $free_subscriptions = Subscription::whereHas('package', function($query) {
            $query->where('regular_price', 0);
        })->status(['active', 'trialing'])->whereBetween('ended_at', [now(), now()->addDays(7)])->get();

        if($free_subscriptions->count()) {
            foreach($free_subscriptions as $subscription) {
                $customer = $subscription->user;
                dispatch(new SubscriptionExpiryReminderMailJob($customer->id, $subscription->id));
            }
        }
        $this->info('Reminder emails sent to customers with expiring subscriptions.');
        return Command::SUCCESS;
    }
}
