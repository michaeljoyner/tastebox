<?php

namespace App\Providers;

use App\Events\AddressGivenForAllUnsetKits;
use App\Events\KitAddressUpdated;
use App\Events\OrderConfirmed;
use App\Listeners\ApplyAddressForAllUnsetKits;
use App\Listeners\CreateMemberProfile;
use App\Listeners\DispatchRewardSignup;
use App\Listeners\SendAdminOrderConfirmedMail;
use App\Listeners\SendCustomerOrderConfirmedMail;
use App\Listeners\SendWelcomeEmail;
use App\Listeners\UpdateSecondaryKitAddresses;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
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
            DispatchRewardSignup::class,
        ],
        Verified::class => [
            SendWelcomeEmail::class,
        ],
        OrderConfirmed::class => [
            SendCustomerOrderConfirmedMail::class,
            SendAdminOrderConfirmedMail::class
        ],

        KitAddressUpdated::class => [
            UpdateSecondaryKitAddresses::class
        ],




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
