<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerCreatedByAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $customer;
    protected $customer_password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customer, $customer_password)
    {
        $this->customer = $customer;
        $this->customer_password = $customer_password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject:  'Account Created By Our '. config('app.name') . ' Team'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.customer-created-by-admin',
            with: [
                'customer' => $this->customer,
                'password' => $this->customer_password
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
