<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\DeleteAccountMail;

class DeleteAccountMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customer_name;
    protected $customer_email;
    protected $deleted_date;
    protected $deleted_time;

    /**
     * Create a new message instance.
     */
    public function __construct($customer_name, $customer_email, $deleted_date, $deleted_time)
    {
        $this->customer_name = $customer_name;
        $this->customer_email = $customer_email;
        $this->deleted_date = $deleted_date;
        $this->deleted_time = $deleted_time;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->customer_email)->send(new DeleteAccountMail($this->customer_name, $this->customer_email, $this->deleted_date, $this->deleted_time));
    }
}
