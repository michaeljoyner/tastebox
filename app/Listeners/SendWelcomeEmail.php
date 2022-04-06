<?php

namespace App\Listeners;

use App\Mail\WelcomeAboard;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail
{

    public function __construct()
    {
        //
    }


    public function handle(Verified $event)
    {
        Mail::to($event->user->email)
            ->queue(new WelcomeAboard($event->user));
    }
}
