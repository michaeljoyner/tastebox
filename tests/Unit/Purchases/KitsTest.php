<?php


namespace Tests\Unit\Purchases;


use App\Meals\Meal;
use App\Orders\Menu;
use App\Purchases\KitMeal;
use App\Purchases\KitMealSummary;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KitsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function kits_can_provide_price()
    {
        $menu = factory(Menu::class)->state('current')->create();

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();

        $menu->setMeals([$mealA->id, $mealB->id, $mealC->id]);

        $basket = ShoppingBasket::for(null);

        $kit = $basket->addKit($menu->id);
        $kit->setMeal($mealA->id, 2);
        $kit->setMeal($mealB->id, 3);
        $kit->setMeal($mealC->id, 4);

        $this->assertEquals(Meal::SERVING_PRICE * 9, $kit->price());
    }

    /**
     *@test
     */
    public function kits_can_provide_a_meal_summary()
    {
        $menu = factory(Menu::class)->state('current')->create();

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();

        $menu->setMeals([$mealA->id, $mealB->id, $mealC->id]);

        $basket = ShoppingBasket::for(null);

        $kit = $basket->addKit($menu->id);
        $kit->setMeal($mealA->id, 2);
        $kit->setMeal($mealB->id, 3);
        $kit->setMeal($mealC->id, 4);

        $meal_summary = $kit->mealSummary();
        $this->assertInstanceOf(KitMealSummary::class, $meal_summary);
        $this->assertCount(3, $meal_summary->meals);

        $this->assertTrue($meal_summary->meals->contains(
            fn (KitMeal $kit_meal) => $kit_meal->name === $mealA->name && $kit_meal->servings === 2
        ));

        $this->assertTrue($meal_summary->meals->contains(
            fn (KitMeal $kit_meal) => $kit_meal->name === $mealB->name && $kit_meal->servings === 3
        ));

        $this->assertTrue($meal_summary->meals->contains(
            fn (KitMeal $kit_meal) => $kit_meal->name === $mealC->name && $kit_meal->servings === 4
        ));
    }
}
