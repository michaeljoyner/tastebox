<?php


namespace Tests\Unit\Purchases;


use App\Meals\Meal;
use App\Meals\MealPriceTier;
use App\Orders\Menu;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppingBasketMealsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function can_add_a_meal_to_a_kit()
    {
        $menu = factory(Menu::class)->create();
        $meal = factory(Meal::class)->create([
            'price_tier' => MealPriceTier::PREMIUM
        ]);
        $menu->setMeals([$meal->id]);

        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);

        $basket->addMealToKit($kit->id, $meal, 3);

        $current_kit = collect(session('basket.kits'))->first(fn ($k) => $k->id === $kit->id);

        $this->assertCount(1, $current_kit->meals);
        $this->assertEquals($meal->id, $current_kit->meals[0]['id']);
        $this->assertEquals(3, $current_kit->meals[0]['servings']);
        $this->assertSame(MealPriceTier::PREMIUM->value, $current_kit->meals[0]['tier']);
    }

    /**
     *@test
     */
    public function adding_existing_meal_to_kit_just_updates_servings()
    {
        $menu = factory(Menu::class)->create();
        $meal = factory(Meal::class)->create();
        $menu->setMeals([$meal->id]);

        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);

        $basket->addMealToKit($kit->id, $meal, 3);

        $current_kit = collect(session('basket.kits'))->first(fn ($k) => $k->id === $kit->id);

        $this->assertCount(1, $current_kit->meals);
        $this->assertEquals($meal->id, $current_kit->meals[0]['id']);
        $this->assertEquals(3, $current_kit->meals[0]['servings']);

        $basket->addMealToKit($kit->id, $meal, 1);

        $current_kit = collect(session('basket.kits'))->first(fn ($k) => $k->id === $kit->id);

        $this->assertCount(1, $current_kit->meals);
        $this->assertEquals($meal->id, $current_kit->meals[0]['id']);
        $this->assertEquals(1, $current_kit->meals[0]['servings']);
    }

    /**
     *@test
     */
    public function remove_meal_from_basket()
    {
        $menu = factory(Menu::class)->create();
        $meal = factory(Meal::class)->create();
        $menu->setMeals([$meal->id]);

        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);

        $basket->addMealToKit($kit->id, $meal, 3);

        $this->assertCount(1, session('basket.kits')[0]->meals);

        $this->assertCount(1, $kit->meals);

        $basket->removeMealFromKit($kit->id, $meal->id);

        $this->assertCount(0, $kit->meals);
        $this->assertCount(0, session('basket.kits')[0]->meals);
    }
}
