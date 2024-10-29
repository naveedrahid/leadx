<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Jobs\SubscriptionCancelledMailJob;

class ExpireSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire user subscriptions if they have reached their expiration date.';

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
                $subscription->update([
                    'status' => 'canceled',
                    'ended_at' => now()
                ]);

                dispatch(new SubscriptionCancelledMailJob($customer->id, $subscription->id));
            }
        }

        $this->info('Subscription status checked and updated successfully.');
        return Command::SUCCESS;
    }
}
