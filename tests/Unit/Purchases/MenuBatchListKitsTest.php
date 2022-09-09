<?php


namespace Tests\Unit\Purchases;


use App\DeliveryAddress;
use App\DeliveryArea;
use App\Meals\Meal;
use App\Orders\Menu;
use App\Purchases\Address;
use App\Purchases\Order;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuBatchListKitsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function list_kits_for_the_batch()
    {
        $menu = factory(Menu::class)->state('current')->create();

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $mealD = factory(Meal::class)->create();
        $mealE = factory(Meal::class)->create();

        $menu->setMeals([
            $mealA->id,
            $mealB->id,
            $mealC->id,
            $mealD->id,
            $mealE->id,
        ]);

        $orderA = factory(Order::class)->state('paid')->create();
        $orderB = factory(Order::class)->state('paid')->create();
        $orderC = factory(Order::class)->state('paid')->create();

        $basket = ShoppingBasket::for(null);
        $kitA = $basket->addKit($menu->id);
        $kitA->setMeal($mealA->id, 2);
        $kitA->setMeal($mealB->id, 3);
        $kitA->setMeal($mealC->id, 4);

        $kitB = $basket->addKit($menu->id);
        $kitB->setMeal($mealC->id, 5);
        $kitB->setMeal($mealD->id, 6);
        $kitB->setMeal($mealE->id, 3);

        $kitC = $basket->addKit($menu->id);
        $kitC->setMeal($mealA->id, 3);
        $kitC->setMeal($mealC->id, 2);
        $kitC->setMeal($mealE->id, 3);

        $test_address = new DeliveryAddress(DeliveryArea::HOWICK, '123 test street');
        $kitA->setDeliveryAddress($test_address);
        $kitB->setDeliveryAddress($test_address);
        $kitC->setDeliveryAddress($test_address);

        $orderA->addKit($kitA);
        $orderB->addKit($kitB);
        $orderC->addKit($kitC);

        $batch = $menu->getBatch();

        $expected = [
            [
                'customer' => $orderA->customer()->toArray(),
                'delivery_address' => $test_address->toArray(),
                'meals' => [
                    ['name' => $mealA->name, 'servings' => 2],
                    ['name' => $mealB->name, 'servings' => 3],
                    ['name' => $mealC->name, 'servings' => 4],
                ]
            ],
            [
                'customer' => $orderB->customer()->toArray(),
                'delivery_address' => $test_address->toArray(),
                'meals' => [
                    ['name' => $mealC->name, 'servings' => 5],
                    ['name' => $mealD->name, 'servings' => 6],
                    ['name' => $mealE->name, 'servings' => 3],
                ]
            ],
            [
                'customer' => $orderC->customer()->toArray(),
                'delivery_address' => $test_address->toArray(),
                'meals' => [
                    ['name' => $mealA->name, 'servings' => 3],
                    ['name' => $mealC->name, 'servings' => 2],
                    ['name' => $mealE->name, 'servings' => 3],
                ]
            ],
        ];

        $this->assertEquals($expected, $batch->kitList());
    }
}
