<?php

namespace Tests\Unit\Purchases;

use App\AddOns\AddOn;
use App\Meals\Meal;
use App\Meals\MealPriceTier;
use App\Orders\Menu;
use App\Purchases\Kit;
use App\Purchases\KitMealSummary;
use App\Purchases\Order;
use App\Purchases\OrderedKit;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ShoppingBasketTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
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
     * @test
     */
    public function can_clear_a_basket()
    {
        $basket = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->create();

        $kit = $basket->addKit($menu->id);

        $basket->clear();

        $this->assertCount(0, session('basket.kits'));
        $this->assertTrue($basket->isEmpty());
    }

    /**
     * @test
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
     * @test
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
     * @test
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
            fn(Kit $k) => $k->id === $kitA->id
        ));
    }

    /**
     * @test
     */
    public function get_kit_by_id()
    {
        $basket = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->create();
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $menu->setMeals([$mealA->id, $mealB->id]);

        $kit = $basket->addKit($menu->id);
        $kit->setMeal($mealA, 2);
        $kit->setMeal($mealB, 3);

        $fetched = $basket->getKit($kit->id)->toArray();

        $expected = [
            'name'    => $kit->name,
            'id'      => $kit->id,
            'menu_id' => $menu->id,
            'meals'   => [
                [
                    'id'       => $mealA->id,
                    'servings' => 2,
                    'tier'     => MealPriceTier::STANDARD->value,
                ],
                [
                    'id'       => $mealB->id,
                    'servings' => 3,
                    'tier'     => MealPriceTier::STANDARD->value,
                ],
            ],
            'add_ons' => [],
        ];

        $this->assertEquals($expected, $fetched);
    }

    /**
     * @test
     */
    public function can_check_if_basket_has_a_kit()
    {
        $basket = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->create();
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $menu->setMeals([$mealA->id, $mealB->id]);

        $kit = $basket->addKit($menu->id);
        $kit->setMeal($mealA, 2);
        $kit->setMeal($mealB, 3);

        $this->assertTrue($basket->hasKit($kit->id));
        $this->assertFalse($basket->hasKit('not-a-kit-id'));
    }

    /**
     * @test
     */
    public function get_the_price_from_the_basket()
    {
        $basket = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->state('upcoming')->create();
        $mealA = factory(Meal::class)->state('budget')->create();
        $mealB = factory(Meal::class)->state('standard')->create();
        $mealC = factory(Meal::class)->state('premium')->create();
        $addOnA = factory(AddOn::class)->create(['price' => 2200]);
        $addOnB = factory(AddOn::class)->create(['price' => 5500]);
        $menu->setMeals([$mealA->id, $mealB->id, $mealC->id]);
        $menu->addOns()->sync([$addOnA->id, $addOnB->id]);

        $kit = $basket->addKit($menu->id);
        $kit->setMeal($mealA, 2);
        $kit->setMeal($mealB, 2);
        $kit->setMeal($mealC, 4);
        $kit->setAddOn($addOnA, 3);
        $kit->setAddOn($addOnB, 5);

        $expected = collect([
            MealPriceTier::BUDGET->price() * 2,
            MealPriceTier::STANDARD->price() * 2,
            MealPriceTier::PREMIUM->price() * 4,
            ($addOnA->price / 100) * 3,
            ($addOnB->price / 100) * 5,
        ])->sum();

        $this->assertEquals($expected, $basket->price());
    }

    /**
     * @test
     */
    public function basket_price_does_not_include_ineligible_kits()
    {
        $basket = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->state('upcoming')->create();
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $menu->setMeals([$mealA->id, $mealB->id, $mealC->id]);

        $bad_kit = $basket->addKit($menu->id);
        $bad_kit->setMeal($mealA, 2);
        $bad_kit->setMeal($mealB, 3);

        $good_kit = $basket->addKit($menu->id);
        $good_kit->setMeal($mealA, 3);
        $good_kit->setMeal($mealB, 4);
        $good_kit->setMeal($mealC, 5);


        $this->assertEquals(12 * Meal::SERVING_PRICE, $basket->price());
    }

    /**
     * @test
     */
    public function kits_are_not_eligible_if_not_for_available_menu()
    {
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $basket = ShoppingBasket::for(null);
        $menuA = factory(Menu::class)->state('upcoming')->create();
        $menuB = factory(Menu::class)->state('old')->create();

        $menuA->setMeals([$mealA->id, $mealB->id, $mealC->id]);
        $menuB->setMeals([$mealA->id, $mealB->id, $mealC->id]);


        $current = $basket->addKit($menuA->id);
        $current->setMeal($mealA, 3);
        $current->setMeal($mealB, 3);
        $current->setMeal($mealC, 3);

        $too_old = $basket->addKit($menuB->id);
        $too_old->setMeal($mealA, 3);
        $too_old->setMeal($mealB, 3);
        $too_old->setMeal($mealC, 3);

        $this->assertTrue($current->eligibleForOrder());
        $this->assertFalse($too_old->eligibleForOrder());
    }

    /**
     * @test
     */
    public function restore_order_with_current_kits_to_basket()
    {
        $menuA = factory(Menu::class)->state('current')->create();
        $menuB = factory(Menu::class)->state('upcoming')->create();

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $mealD = factory(Meal::class)->create();
        $mealE = factory(Meal::class)->create();


        $kitA_meals = new KitMealSummary(
            meals: collect([
                ['id' => $mealA->id, 'servings' => 2],
                ['id' => $mealB->id, 'servings' => 3],
                ['id' => $mealC->id, 'servings' => 4],
            ])
        );
        $kitB_meals = new KitMealSummary(
            meals: collect([
                ['id' => $mealB->id, 'servings' => 2],
                ['id' => $mealD->id, 'servings' => 3],
                ['id' => $mealE->id, 'servings' => 4],
            ])
        );

        $order = factory(Order::class)->state('created')->create();
        $ordered_kitA = factory(OrderedKit::class)->create(['order_id' => $order->id, 'menu_id' => $menuA->id]);
        $ordered_kitA->setMeals($kitA_meals);
        $ordered_kitB = factory(OrderedKit::class)->create(['order_id' => $order->id, 'menu_id' => $menuB->id]);
        $ordered_kitB->setMeals($kitB_meals);


        $basket = ShoppingBasket::for(null);

        $basket->restoreFromOrder($order);

        $this->assertCount(2, $basket->kits);

        $kit_one = $basket->kits->first(fn(Kit $kit) => $kit->menu_id === $menuA->id);
        $this->assertCount(3, $kit_one->meals);
        $this->assertTrue($kit_one->meals->contains(fn(
            $meal
        ) => $meal['id'] === $mealA->id && $meal['servings'] === 2));
        $this->assertTrue($kit_one->meals->contains(fn(
            $meal
        ) => $meal['id'] === $mealB->id && $meal['servings'] === 3));
        $this->assertTrue($kit_one->meals->contains(fn(
            $meal
        ) => $meal['id'] === $mealC->id && $meal['servings'] === 4));
        $this->assertSame($ordered_kitA->deliveryAddress()->area, $kit_one->delivery_address->area);
        $this->assertSame($ordered_kitA->deliveryAddress()->address, $kit_one->delivery_address->address);

        $kit_two = $basket->kits->first(fn(Kit $kit) => $kit->menu_id === $menuB->id);
        $this->assertCount(3, $kit_two->meals);
        $this->assertTrue($kit_two->meals->contains(fn(
            $meal
        ) => $meal['id'] === $mealB->id && $meal['servings'] === 2));
        $this->assertTrue($kit_two->meals->contains(fn(
            $meal
        ) => $meal['id'] === $mealD->id && $meal['servings'] === 3));
        $this->assertTrue($kit_two->meals->contains(fn(
            $meal
        ) => $meal['id'] === $mealE->id && $meal['servings'] === 4));
        $this->assertSame($ordered_kitB->deliveryAddress()->area, $kit_two->delivery_address->area);
        $this->assertSame($ordered_kitB->deliveryAddress()->address, $kit_two->delivery_address->address);
    }

    /**
     * @test
     */
    public function restore_order_with_with_illegible_kits_to_basket()
    {
        $menuA = factory(Menu::class)->state('old')->create();
        $menuB = factory(Menu::class)->state('upcoming')->create();

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $mealD = factory(Meal::class)->create();
        $mealE = factory(Meal::class)->create();


        $kitA_meals = new KitMealSummary(
            meals: collect([
                ['id' => $mealA->id, 'servings' => 2],
                ['id' => $mealB->id, 'servings' => 3],
                ['id' => $mealC->id, 'servings' => 4],
            ])
        );
        $kitB_meals = new KitMealSummary(
            meals: collect([
                ['id' => $mealB->id, 'servings' => 2],
                ['id' => $mealD->id, 'servings' => 3],
                ['id' => $mealE->id, 'servings' => 4],
            ])
        );

        $order = factory(Order::class)->state('created')->create();
        $ordered_kitA = factory(OrderedKit::class)->create(['order_id' => $order->id, 'menu_id' => $menuA->id]);
        $ordered_kitA->setMeals($kitA_meals);
        $ordered_kitB = factory(OrderedKit::class)->create(['order_id' => $order->id, 'menu_id' => $menuB->id]);
        $ordered_kitB->setMeals($kitB_meals);


        $basket = ShoppingBasket::for(null);

        $basket->restoreFromOrder($order);

        $this->assertCount(1, $basket->kits);


        $new_kit = $basket->kits->first(fn(Kit $kit) => $kit->menu_id === $menuB->id);
        $this->assertCount(3, $new_kit->meals);
        $this->assertTrue($new_kit->meals->contains(fn(
            $meal
        ) => $meal['id'] === $mealB->id && $meal['servings'] === 2));
        $this->assertTrue($new_kit->meals->contains(fn(
            $meal
        ) => $meal['id'] === $mealD->id && $meal['servings'] === 3));
        $this->assertTrue($new_kit->meals->contains(fn(
            $meal
        ) => $meal['id'] === $mealE->id && $meal['servings'] === 4));
        $this->assertSame($ordered_kitB->deliveryAddress()->area, $new_kit->delivery_address->area);
        $this->assertSame($ordered_kitB->deliveryAddress()->address, $new_kit->delivery_address->address);
    }

    /**
     * @test
     */
    public function restoring_order_clears_whatever_you_have_in_your_basket()
    {
        $menuA = factory(Menu::class)->state('current')->create();

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $mealD = factory(Meal::class)->create();
        $mealE = factory(Meal::class)->create();


        $kitA_meals = new KitMealSummary(
            meals: collect([
                ['id' => $mealA->id, 'servings' => 2],
                ['id' => $mealB->id, 'servings' => 3],
                ['id' => $mealC->id, 'servings' => 4],
            ])
        );
        $kitB_meals = new KitMealSummary(
            meals: collect([
                ['id' => $mealB->id, 'servings' => 2],
                ['id' => $mealD->id, 'servings' => 3],
                ['id' => $mealE->id, 'servings' => 4],
            ])
        );

        $order = factory(Order::class)->state('created')->create();
        $ordered_kitA = factory(OrderedKit::class)->create(['order_id' => $order->id, 'menu_id' => $menuA->id]);
        $ordered_kitA->setMeals($kitA_meals);


        $basket = ShoppingBasket::for(null);
        $old_kit = $basket->addKit($menuA->id);
        $old_kit->setMeal($mealB, 3);
        $old_kit->setMeal($mealD, 3);
        $old_kit->setMeal($mealE, 3);

        $basket->restoreFromOrder($order);

        $this->assertCount(1, $basket->kits);

        $kit_one = $basket->kits->first(fn(Kit $kit) => $kit->menu_id === $menuA->id);
        $this->assertCount(3, $kit_one->meals);
        $this->assertTrue($kit_one->meals->contains(fn(
            $meal
        ) => $meal['id'] === $mealA->id && $meal['servings'] === 2));
        $this->assertTrue($kit_one->meals->contains(fn(
            $meal
        ) => $meal['id'] === $mealB->id && $meal['servings'] === 3));
        $this->assertTrue($kit_one->meals->contains(fn(
            $meal
        ) => $meal['id'] === $mealC->id && $meal['servings'] === 4));
        $this->assertSame($ordered_kitA->deliveryAddress()->area, $kit_one->delivery_address->area);
        $this->assertSame($ordered_kitA->deliveryAddress()->address, $kit_one->delivery_address->address);
    }
}
