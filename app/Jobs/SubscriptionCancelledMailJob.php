<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionCancelledMail;
use App\Models\{
    User,
    Subscription
};

class SubscriptionCancelledMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customer_id;
    protected $subscription_id;

    /**
     * Create a new job instance.
     */
    public function __construct($customer_id, $subscription_id)
    {
        $this->customer_id = $customer_id;
        $this->subscription_id = $subscription_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $customer = User::customer()->whereId($this->customer_id)->first();
        $subscription = Subscription::whereId($this->subscription_id)->first();
        Mail::to($customer->email)->send(new SubscriptionCancelledMail($customer, $subscription));
    }
}
