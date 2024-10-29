<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerCardAddedMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $customer;
    protected $customer_card;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($customer, $customer_card)
    {
        $this->customer = $customer;
        $this->customer_card = $customer_card;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('app.name') .': New Payment Method Added'
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
            markdown: 'mail.customer-card-added',
            with: [
                'customer' => $this->customer,
                'customer_card' => $this->customer_card
            ],
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
