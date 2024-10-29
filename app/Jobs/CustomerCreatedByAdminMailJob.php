<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomerCreatedByAdminMail;

class CustomerCreatedByAdminMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customer;
    protected $customer_password;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($customer, $customer_password)
    {
        $this->customer = $customer;
        $this->customer_password = $customer_password;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->customer->email)->send(new CustomerCreatedByAdminMail($this->customer, $this->customer_password));
    }
}
