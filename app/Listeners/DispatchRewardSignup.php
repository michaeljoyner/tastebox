<?php

namespace App\Listeners;

use App\Jobs\RewardSignUp;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DispatchRewardSignup
{


    public function handle(Registered $event)
    {
        RewardSignUp::dispatch($event->user);
    }
}
