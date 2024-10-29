<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomerPaymentLinkGenerateByAdminMail;

class CustomerPaymentLinkGenerateByAdminMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customer;
    protected $package;
    protected $paymentlink;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($customer, $package, $paymentlink)
    {
        $this->customer = $customer;
        $this->package = $package;
        $this->paymentlink = $paymentlink;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->customer->email)->send(new CustomerPaymentLinkGenerateByAdminMail($this->customer, $this->package, $this->paymentlink));
    }
}
