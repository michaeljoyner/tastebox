<?php

namespace Tests\Unit\Purchases;

use App\AddOns\AddOn;
use App\Meals\Meal;
use App\Meals\MealPriceTier;
use App\Orders\Menu;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ShoppingBasketAddOnsTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * @test
     */
    public function can_add_an_add_on_the_the_basket()
    {
        $menu = factory(Menu::class)->create();
        $addOn = factory(AddOn::class)->create([
            'price' => 1000
        ]);
        $menu->addOns()->attach([$addOn->id]);

        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);

        $basket->addAddOnToKit($kit->id, $addOn, 3);

        $current_kit = collect(session('basket.kits'))->first(fn($k) => $k->id === $kit->id);

        $this->assertCount(1, $current_kit->addOns);
        $this->assertEquals($addOn->id, $current_kit->addOns[0]['id']);
        $this->assertEquals(3, $current_kit->addOns[0]['qty']);
        $this->assertSame(1000, $current_kit->addOns[0]['price']);
    }

    /**
     *@test
     */
    public function can_add_existing_add_on_to_update_qty()
    {
        $menu = factory(Menu::class)->create();
        $addOn = factory(AddOn::class)->create([
            'price' => 1000
        ]);
        $menu->addOns()->attach([$addOn->id]);

        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);

        $basket->addAddOnToKit($kit->id, $addOn, 3);
        $basket->addAddOnToKit($kit->id, $addOn, 2);

        $current_kit = collect(session('basket.kits'))->first(fn($k) => $k->id === $kit->id);

        $this->assertCount(1, $current_kit->addOns);
        $this->assertEquals($addOn->id, $current_kit->addOns[0]['id']);
        $this->assertEquals(2, $current_kit->addOns[0]['qty']);
        $this->assertSame(1000, $current_kit->addOns[0]['price']);
    }

    /**
     *@test
     */
    public function can_remove_an_add_on_from_the_kit()
    {
        $menu = factory(Menu::class)->create();
        $addOn = factory(AddOn::class)->create([
            'price' => 1000
        ]);
        $menu->addOns()->attach([$addOn->id]);

        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);

        $basket->addAddOnToKit($kit->id, $addOn, 3);
        $basket->removeAddOnFromKit($kit->id, $addOn->uuid);

        $current_kit = collect(session('basket.kits'))->first(fn($k) => $k->id === $kit->id);

        $this->assertCount(0, $current_kit->addOns);
    }
}
