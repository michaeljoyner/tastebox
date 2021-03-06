<?php


namespace Tests\Unit\Purchases;


use App\DatePresenter;
use App\Meals\Meal;
use App\Orders\Menu;
use App\Purchases\Address;
use App\Purchases\Order;
use App\Purchases\OrderedKit;
use App\Purchases\OrderedKitAdminSummary;
use App\Purchases\OrderedKitPresenter;
use App\Purchases\OrderedKitSummary;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PresentOrderedKitsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function present_an_ordered_kit()
    {
        $menu = factory(Menu::class)->create();
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $mealD = factory(Meal::class)->create();
        $menu->setMeals([$mealA->id, $mealB->id, $mealC->id, $mealD->id]);

        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);
        $kit->setMeal($mealA->id, 3);
        $kit->setMeal($mealB->id, 4);
        $kit->setMeal($mealC->id, 5);
        $kit->setMeal($mealD->id, 6);

        $order = factory(Order::class)->create();
        $ordered_kit = $order->addKit($kit, new Address([
            'line_one' => '123 Test street',
            'line_two' => 'Unit A',
            'city' => 'testville',
            'postal_code' => '402',
        ]));

        $presented = $ordered_kit->summarize();

        $expected_meals = [
            ['meal' => $mealA->name, 'servings' => 3],
            ['meal' => $mealB->name, 'servings' => 4],
            ['meal' => $mealC->name, 'servings' => 5],
            ['meal' => $mealD->name, 'servings' => 6],
        ];

        $this->assertInstanceOf(OrderedKitSummary::class, $presented);
        $this->assertSame(DatePresenter::prettyWithDay($menu->delivery_from), $presented->delivery_date);
        $this->assertSame($expected_meals, $presented->meals);
        $this->assertSame('123 Test street, Unit A, testville', $presented->delivery_address);

    }

    /**
     *@test
     */
    public function summarize_ordered_kit_for_admin()
    {
        $menu = factory(Menu::class)->create();
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $mealD = factory(Meal::class)->create();
        $menu->setMeals([$mealA->id, $mealB->id, $mealC->id, $mealD->id]);

        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);
        $kit->setMeal($mealA->id, 3);
        $kit->setMeal($mealB->id, 4);
        $kit->setMeal($mealC->id, 5);
        $kit->setMeal($mealD->id, 6);

        $order = factory(Order::class)->create();
        $ordered_kit = $order->addKit($kit, new Address([
            'line_one' => '123 Test street',
            'line_two' => 'Unit A',
            'city' => 'testville',
            'postal_code' => '402',
        ]));

        $presented = $ordered_kit->summarizeForAdmin();

        $expected_meals = [
            ['meal' => $mealA->name, 'servings' => 3],
            ['meal' => $mealB->name, 'servings' => 4],
            ['meal' => $mealC->name, 'servings' => 5],
            ['meal' => $mealD->name, 'servings' => 6],
        ];

        $this->assertInstanceOf(OrderedKitAdminSummary::class, $presented);
        $this->assertSame(DatePresenter::pretty($menu->delivery_from), $presented->delivery_date);
        $this->assertSame($expected_meals, $presented->meals);
        $this->assertSame('123 Test street, Unit A, testville', $presented->delivery_address);
        $this->assertSame(OrderedKit::STATUS_DUE, $presented->status);
    }
}
