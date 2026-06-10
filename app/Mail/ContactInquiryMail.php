<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, string>  $data
     */
    public function __construct(public readonly array $data) {}

    public function envelope(): Envelope
    {
        $replyTo = [];

        if (! empty($this->data['email'])) {
            $replyTo[] = new Address($this->data['email'], $this->data['name']);
        }

        return new Envelope(
            subject: 'New inquiry from '.$this->data['name'].' — '.$this->data['product_interest'].' | varidianlab.com',
            replyTo: $replyTo,
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.contact-inquiry-text',
        );
    }
}
