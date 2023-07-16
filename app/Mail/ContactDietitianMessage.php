<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactDietitianMessage extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct(
        public string $sender,
        public string $email,
        public string $phone,
        public string $location,
        public string $message,
    ) {
    }


    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: $this->email,
            subject: "{$this->sender} is looking for a dietitian",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.general.contact-dietitian',
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
