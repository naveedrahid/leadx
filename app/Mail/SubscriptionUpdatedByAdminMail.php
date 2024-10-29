<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionUpdatedByAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $customer;
    protected $subscription;
    protected $package;
    protected $coupon;

    /**
     * Create a new job instance.
     */
    public function __construct($customer, $subscription, $package, $coupon)
    {
        $this->customer = $customer;
        $this->subscription = $subscription;
        $this->package = $package;
        $this->coupon = $coupon;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Subscription Updated By '. config('app.name') .' Team'
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
            markdown: 'mail.subscription-updated-by-admin',
            with: [
                'customer' => $this->customer,
                'subscription' => $this->subscription,
                'package' => $this->package,
                'coupon' => $this->coupon
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
