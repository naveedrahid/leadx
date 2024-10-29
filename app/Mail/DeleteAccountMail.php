<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DeleteAccountMail extends Mailable
{
    use Queueable, SerializesModels;

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
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('app.name') .': Your account has been deleted!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.delete-account',
            with: [
                'customer_name' => $this->customer_name,
                'customer_email' => $this->customer_email,
                'deleted_date' => $this->deleted_date,
                'deleted_time' => $this->deleted_time
            ]
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
