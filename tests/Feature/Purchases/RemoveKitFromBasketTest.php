<?php


namespace Tests\Feature\Purchases;


use App\Orders\Menu;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RemoveKitFromBasketTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function delete_a_kit_from_the_basket()
    {
        $this->withoutExceptionHandling();

        $menu = factory(Menu::class)->create();
        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);

        $this->assertCount(1, session('basket.kits', []));

        $response = $this->asGuest()->deleteJson("/my-kits/{$kit->id}");
        $response->assertSuccessful();

        $this->assertCount(0, session('basket.kits', []));
    }
}
