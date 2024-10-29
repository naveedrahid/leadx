<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerPaymentLinkGenerateByAdminMail extends Mailable
{
    use Queueable, SerializesModels;

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
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $title = ucwords(str_replace('plan', '', strtolower($this->package->title))) . ' Plan';
        return new Envelope(
            subject: config('app.name') .': Your Payment Link for '. $title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.customer-payment-link',
            with: [
                'customer' => $this->customer,
                'package' => $this->package,
                'paymentlink' => $this->paymentlink
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
