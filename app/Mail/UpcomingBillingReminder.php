<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UpcomingBillingReminder extends Mailable
{
    use Queueable, SerializesModels;

    protected $customer;
    protected $subscription;

    /**
     * Create a new job instance.
     */
    public function __construct($customer, $subscription)
    {
        $this->customer = $customer;
        $this->subscription = $subscription;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('app.name') .': Upcoming Billing Reminder'
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.upcoming-billing',
            with: [
                'customer' => $this->customer,
                'subscription' => $this->subscription
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [];
    }
}
