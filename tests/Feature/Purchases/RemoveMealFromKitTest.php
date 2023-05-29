<?php


namespace Tests\Feature\Purchases;


use App\Meals\Meal;
use App\Orders\Menu;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RemoveMealFromKitTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function remove_a_meal_from_a_kit()
    {
        $this->withoutExceptionHandling();
        $menu = factory(Menu::class)->create();
        $meal = factory(Meal::class)->create();
        $menu->setMeals([$meal->id]);

        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);
        $basket->addMealToKit($kit->id, $meal, 3);

        $response = $this->asGuest()->deleteJson("/my-kits/{$kit->id}/meals/{$meal->id}");
        $response->assertSuccessful();

        $kit = session('basket.kits')[0];

        $this->assertCount(0, $kit->meals);
    }
}
