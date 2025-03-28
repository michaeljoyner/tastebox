<?php


namespace Tests\Unit\Purchases;


use App\AddOns\AddOn;
use App\DeliveryAddress;
use App\DeliveryArea;
use App\Meals\Meal;
use App\Meals\MealPriceTier;
use App\Orders\Menu;
use App\Purchases\Kit;
use App\Purchases\KitAddOn;
use App\Purchases\KitMeal;
use App\Purchases\KitMealSummary;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class KitsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function kits_can_provide_price()
    {
        $menu = factory(Menu::class)->state('current')->create();

        $mealA = factory(Meal::class)->create([
            'price_tier' => MealPriceTier::BUDGET,
        ]);
        $mealB = factory(Meal::class)->create([
            'price_tier' => MealPriceTier::STANDARD,
        ]);
        $mealC = factory(Meal::class)->create([
            'price_tier' => MealPriceTier::PREMIUM,
        ]);

        $menu->setMeals([$mealA->id, $mealB->id, $mealC->id]);

        $basket = ShoppingBasket::for(null);

        $kit = $basket->addKit($menu->id);
        $kit->setMeal($mealA, 2);
        $kit->setMeal($mealB, 3);
        $kit->setMeal($mealC, 4);

        //expect 2 x BUDGET, 3 x STANDARD and 4 x PREMIUM
        $expected_price = collect([
            MealPriceTier::BUDGET->price() * 2,
            MealPriceTier::STANDARD->price() * 3,
            MealPriceTier::PREMIUM->price() * 4,
        ])->sum();

        $this->assertEquals($expected_price, $kit->price());
    }

    /**
     * @test
     */
    public function kits_can_check_if_still_valid()
    {
        $old_menu = factory(Menu::class)->state('old')->create();
        $current_menu = factory(Menu::class)->state('current')->create([
            'current_to' => Carbon::tomorrow()
        ]);

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();

        $old_menu->setMeals([$mealA->id, $mealB->id, $mealC->id]);
        $current_menu->setMeals([$mealA->id, $mealB->id, $mealC->id]);

        $basket = ShoppingBasket::for(null);

        $kitA = $basket->addKit($old_menu->id);
        $kitA->setMeal($mealA, 2);
        $kitA->setMeal($mealB, 3);
        $kitA->setMeal($mealC, 4);

        $this->assertFalse($kitA->isValid());


        $kitB = $basket->addKit($current_menu->id);
        $kitB->setMeal($mealA, 2);
        $kitB->setMeal($mealB, 3);
        $kitB->setMeal($mealC, 4);

        $this->assertTrue($kitB->isValid());
    }

    /**
     * @test
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
        $kit->setMeal($mealA, 2);
        $kit->setMeal($mealB, 3);
        $kit->setMeal($mealC, 4);

        $meal_summary = $kit->mealSummary();
        $this->assertInstanceOf(KitMealSummary::class, $meal_summary);
        $this->assertCount(3, $meal_summary->meals);

        $this->assertTrue($meal_summary->meals->contains(
            fn(KitMeal $kit_meal) => $kit_meal->name === $mealA->name && $kit_meal->servings === 2
        ));

        $this->assertTrue($meal_summary->meals->contains(
            fn(KitMeal $kit_meal) => $kit_meal->name === $mealB->name && $kit_meal->servings === 3
        ));

        $this->assertTrue($meal_summary->meals->contains(
            fn(KitMeal $kit_meal) => $kit_meal->name === $mealC->name && $kit_meal->servings === 4
        ));
    }

    /**
     * @test
     */
    public function a_kit_has_an_add_on_summary()
    {
        $menu = factory(Menu::class)->state('current')->create();

        $addOnA = factory(AddOn::class)->create();
        $addOnB = factory(AddOn::class)->create();

        $menu->addOns()->sync([$addOnA->id, $addOnB->id]);

        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);
        $kit->setAddOn($addOnA, 3);
        $kit->setAddOn($addOnB, 2);

        $kit_summary = $kit->addOnSummary();

        $this->assertCount(2, $kit_summary->addOns);

        $this->assertTrue(
            $kit_summary
                ->addOns
                ->contains(
                    fn(KitAddOn $kit_add_on) => $kit_add_on->name === $addOnA->name
                        && $kit_add_on->qty === 3
                        && $kit_add_on->add_on_id === $addOnA->id,
                )
        );

        $this->assertTrue(
            $kit_summary
                ->addOns
                ->contains(
                    fn(KitAddOn $kit_add_on) => $kit_add_on->name === $addOnB->name
                        && $kit_add_on->qty === 2
                        && $kit_add_on->add_on_id === $addOnB->id,
                )
        );
    }

    /**
     * @test
     */
    public function kits_with_less_than_three_meals_is_not_legible_for_order()
    {
        $menu = factory(Menu::class)->state('upcoming')->create();

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();

        $menu->setMeals([$mealA->id, $mealB->id, $mealC->id]);

        $basket = ShoppingBasket::for(null);

        $kit = $basket->addKit($menu->id);
        $kit->setMeal($mealA, 2);
        $kit->setMeal($mealB, 3);

        $this->assertFalse($kit->eligibleForOrder());

        $kit->setMeal($mealC, 3);
        $this->assertTrue($kit->eligibleForOrder());
    }

    /**
     * @test
     */
    public function can_set_the_delivery_address_for_a_kit()
    {
        $menu = factory(Menu::class)->create();
        $kit = new Kit($menu->id, collect([]), collect([]), DeliveryAddress::fake());

        $new_address = new DeliveryAddress(DeliveryArea::HILTON, '123 test street');

        $kit->setDeliveryAddress($new_address);

        $this->assertSame(DeliveryArea::HILTON, $kit->delivery_address->area);
        $this->assertSame('123 test street', $kit->delivery_address->address);
    }
}
