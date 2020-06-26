<?php

namespace Tests\Unit\Purchases;

use App\Orders\Menu;
use App\Purchases\Kit;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ShoppingBasketTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function add_kit_to_basket()
    {
        $basket = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->create();

        $kit = $basket->addKit($menu->id);

        $this->assertEquals($menu->id, $kit->menu_id);
        $this->assertTrue(Str::isUuid($kit->id));

        $this->assertCount(1, session('basket.kits'));
        $this->assertEquals($kit, session('basket.kits')[0]);
    }

    /**
     *@test
     */
    public function can_add_a_second_kit_with_same_menu()
    {
        $basket = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->create();

        $kitA = $basket->addKit($menu->id);
        $kitB = $basket->addKit($menu->id);


        $this->assertEquals($menu->id, $kitA->menu_id);
        $this->assertTrue(Str::isUuid($kitA->id));

        $this->assertEquals($menu->id, $kitB->menu_id);
        $this->assertTrue(Str::isUuid($kitB->id));

        $this->assertNotEquals($kitA->id, $kitB->id);

        $this->assertCount(2, session('basket.kits'));
        $this->assertEquals($kitA, session('basket.kits')[0]);
        $this->assertEquals($kitB, session('basket.kits')[1]);
    }

    /**
     *@test
     */
    public function get_menu_for_kit()
    {
        $basket = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->create();

        $kit = $basket->addKit($menu->id);

        $kit_menu = $basket->getMenuForKit($kit->id);

        $this->assertTrue($kit_menu->is($menu));
    }

    /**
     *@test
     */
    public function can_discard_a_kit()
    {
        $basket = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->create();
        $kitA = $basket->addKit($menu->id);
        $kitB = $basket->addKit($menu->id);

        $basket->discardKit($kitA->id);

        $this->assertCount(1, $basket->kits);
        $this->assertFalse($basket->kits->contains(
            fn (Kit $k) => $k->id === $kitA->id
        ));

    }




}
