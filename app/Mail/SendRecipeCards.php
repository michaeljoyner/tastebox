<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendRecipeCards extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public string $zip_location)
    {}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('tastebox@tastebox.co.za', 'TasteBox HQ')
            ->subject("The week's recipe cards")
            ->markdown('email.admin.recipe-cards')
            ->attach($this->zip_location);
    }
}
