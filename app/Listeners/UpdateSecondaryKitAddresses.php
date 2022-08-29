<?php

namespace App\Listeners;

use App\Events\KitAddressUpdated;
use App\Purchases\ShoppingBasket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateSecondaryKitAddresses
{

    public function handle(KitAddressUpdated $event)
    {
        $basket = ShoppingBasket::for(request()->user());

        $basket->updateSecondaryKitAddresses($event->kit_id, $event->address);
    }
}
