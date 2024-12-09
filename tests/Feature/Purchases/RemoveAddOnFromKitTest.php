<?php

namespace Tests\Feature\Purchases;

use App\AddOns\AddOn;
use App\Orders\Menu;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class RemoveAddOnFromKitTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     *@test
     */
    public function remove_existing_add_on_from_kit()
    {
        $menu = factory(Menu::class)->create();
        $addOn = factory(AddOn::class)->create();
        $menu->addOns()->attach($addOn->id);

        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);
        $kit->setAddOn($addOn, 3);

        $response = $this
            ->asGuest()
            ->deleteJson("/my-kits/{$kit->id}/add-ons/{$addOn->uuid}");
        $response->assertSuccessful();

        $kit = session("basket.kits")[0];

        $this->assertCount(0, $kit->addOns);
    }
}
