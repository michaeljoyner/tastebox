<?php

namespace Tests\Unit\Purchases;

use App\Meals\Meal;
use App\Orders\Menu;
use App\Purchases\Kit;
use App\Purchases\Order;
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
     *@test
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
        $kit->setMeal($mealA->id, 2);
        $kit->setMeal($mealB->id, 3);

        $fetched = $basket->getKit($kit->id)->toArray();

        $expected = [
            'name'    => $kit->name,
            'id'      => $kit->id,
            'menu_id' => $menu->id,
            'meals'   => [
                [
                    'id'       => $mealA->id,
                    'servings' => 2,
                ],
                [
                    'id'       => $mealB->id,
                    'servings' => 3,
                ],
            ]
        ];

        $this->assertEquals($expected, $fetched);
    }

    /**
     *@test
     */
    public function can_check_if_basket_has_a_kit()
    {
        $basket = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->create();
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $menu->setMeals([$mealA->id, $mealB->id]);

        $kit = $basket->addKit($menu->id);
        $kit->setMeal($mealA->id, 2);
        $kit->setMeal($mealB->id, 3);

        $this->assertTrue($basket->hasKit($kit->id));
        $this->assertFalse($basket->hasKit('not-a-kit-id'));
    }

    /**
     *@test
     */
    public function get_the_price_from_the_basket()
    {
        $basket = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->create();
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $menu->setMeals([$mealA->id, $mealB->id, $mealC->id]);

        $kit = $basket->addKit($menu->id);
        $kit->setMeal($mealA->id, 2);
        $kit->setMeal($mealB->id, 4);
        $kit->setMeal($mealC->id, 2);

        $this->assertEquals(8 * Meal::SERVING_PRICE, $basket->price());
    }

    /**
     *@test
     */
    public function basket_price_does_not_include_ineligible_kits()
    {
        $basket = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->create();
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $menu->setMeals([$mealA->id, $mealB->id, $mealC->id]);

        $bad_kit = $basket->addKit($menu->id);
        $bad_kit->setMeal($mealA->id, 2);
        $bad_kit->setMeal($mealB->id, 3);

        $good_kit = $basket->addKit($menu->id);
        $good_kit->setMeal($mealA->id, 3);
        $good_kit->setMeal($mealB->id, 4);
        $good_kit->setMeal($mealC->id, 5);


        $this->assertEquals(12 * Meal::SERVING_PRICE, $basket->price());
    }


}
