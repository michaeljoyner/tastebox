<?php

namespace Tests\Feature\Purchases;

use App\AddOns\AddOn;
use App\Orders\Menu;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AddAddOnToKitTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     *@test
     */
    public function add_an_add_on_to_a_kit()
    {
        $menu = factory(Menu::class)->create();
        $addOn = factory(AddOn::class)->create();
        $menu->addOns()->attach($addOn->id);

        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);

        $response = $this->asGuest()->postJson("/my-kits/{$kit->id}/add-ons", [
            'add_on_id' => $addOn->id,
            'qty' => 3,
        ]);
        $response->assertSuccessful();

        $kits = session('basket.kits');
        $current_kit = collect($kits)->first(fn ($k) => $k->id === $kit->id);
        $this->assertCount(1, $current_kit->addOns);
        $this->assertEquals($addOn->id, $current_kit->addOns[0]['id']);
        $this->assertEquals(3, $current_kit->addOns[0]['qty']);
    }

    /**
     *@test
     */
    public function the_add_on_id_must_exist_on_add_ons_table()
    {
        $menu = factory(Menu::class)->create();

        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);

        $response = $this->asGuest()->postJson("/my-kits/{$kit->id}/add-ons", [
            'add_on_id' => 99,
            'qty' => 3,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     *@test
     */
    public function the_addon_must_be_on_the_menu()
    {
        $menu = factory(Menu::class)->create();
        $addOn = factory(AddOn::class)->create();
        $menu->addOns()->sync([]);

        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);

        $response = $this->asGuest()->postJson("/my-kits/{$kit->id}/add-ons", [
            'add_on_id' => $addOn->id,
            'qty' => 3,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     *@test
     */
    public function the_qty_is_required_as_an_integer()
    {
        $menu = factory(Menu::class)->create();
        $addOn = factory(AddOn::class)->create();
        $menu->addOns()->attach($addOn->id);

        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);

        $response = $this->asGuest()->postJson("/my-kits/{$kit->id}/add-ons", [
            'add_on_id' => $addOn->id,
            'qty' => 'not-an-integer',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
