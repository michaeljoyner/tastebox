<?php

namespace App\Providers;

use App\Events\OrderConfirmed;
use App\Listeners\CreateMemberProfile;
use App\Listeners\SendAdminOrderConfirmedMail;
use App\Listeners\SendCustomerOrderConfirmedMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            CreateMemberProfile::class,
            SendEmailVerificationNotification::class,
        ],
        OrderConfirmed::class => [
            SendCustomerOrderConfirmedMail::class,
            SendAdminOrderConfirmedMail::class
        ]

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
