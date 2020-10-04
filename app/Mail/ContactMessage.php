<?php

namespace App\Mail;

use App\MessageDetails;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $sender_name;
    public $sender_email;
    public $message;
    public $phone;

    public function __construct(MessageDetails $message)
    {
        $this->sender_email= $message->email;
        $this->sender_name = $message->name;
        $this->phone = $message->phone;
        $this->message = $message->message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject("Contact message from {$this->sender_name}")
            ->from($this->sender_email)
            ->markdown('emails.general.contact');
    }
}
