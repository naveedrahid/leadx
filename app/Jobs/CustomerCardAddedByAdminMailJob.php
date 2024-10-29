<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomerCardAddedByAdminMail;
use App\Models\User;

class CustomerCardAddedByAdminMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customer_id;
    protected $customer_card_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($customer_id, $customer_card_id)
    {
        $this->customer_id = $customer_id;
        $this->customer_card_id = $customer_card_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $customer = User::customer()->whereId($this->customer_id)->first();
        $customer_card = $customer->payment_cards()->whereId($this->customer_card_id)->first();
        Mail::to($customer->email)->send(new CustomerCardAddedByAdminMail($customer, $customer_card));
    }
}
