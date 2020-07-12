<?php


namespace Tests\Unit\Purchases;


use App\Meals\Meal;
use App\Orders\Menu;
use App\Purchases\Address;
use App\Purchases\Order;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrdersTest extends TestCase
{
    use RefreshDatabase;


    /**
     *@test
     */
    public function make_a_new_order()
    {
        $order = Order::makeNew([
            'first_name' => 'test first name',
            'last_name' => 'test last name',
            'email' => 'test@test.test',
            'phone' => 'test phone',
        ], 550);

        $this->assertEquals('test first name', $order->first_name);
        $this->assertEquals('test last name', $order->last_name);
        $this->assertEquals('test phone', $order->phone);
        $this->assertEquals('test@test.test', $order->email);
        $this->assertTrue(Str::isUuid($order->order_key));
        $this->assertEquals(55000, $order->price_in_cents);
    }

    /**
     *@test
     */
    public function add_kit_to_order()
    {
        $menu = factory(Menu::class)->state('current')->create();
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $mealD = factory(Meal::class)->create();
        $menu->setMeals([$mealA->id, $mealB->id, $mealC->id, $mealD->id,]);

        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);
        $kit->setMeal($mealA->id, 2);
        $kit->setMeal($mealB->id, 3);
        $kit->setMeal($mealC->id, 4);
        $kit->setMeal($mealD->id, 5);

        $meal_summary = [
            ['id' => $mealA->id, 'name' => $mealA->name, 'servings' => 2],
            ['id' => $mealB->id, 'name' => $mealB->name, 'servings' => 3],
            ['id' => $mealC->id, 'name' => $mealC->name, 'servings' => 4],
            ['id' => $mealD->id, 'name' => $mealD->name, 'servings' => 5],
        ];

        $order = factory(Order::class)->state('unpaid')->create();
        $address = new Address([
            'line_one'    => 'test road',
            'line_two'    => 'test district',
            'city'        => 'test city',
            'postal_code' => 'test code',
            'notes'       => 'test notes',
        ]);

        $orderedKit = $order->addKit($kit, $address);

        $this->assertEquals($kit->id, $orderedKit->kit_id);
        $this->assertEquals($menu->id, $orderedKit->menu_id);
        $this->assertEquals($menu->current_from->week, $orderedKit->menu_week_number);
        $this->assertEquals($menu->delivery_from, $orderedKit->delivery_date);
        $this->assertEquals('test road', $orderedKit->line_one);
        $this->assertEquals('test district', $orderedKit->line_two);
        $this->assertEquals('test city', $orderedKit->city);
        $this->assertEquals('test code', $orderedKit->postal_code);
        $this->assertEquals('test notes', $orderedKit->delivery_notes);
        $this->assertEquals($meal_summary, $orderedKit->meal_summary);

        $this->assertCount(4, $orderedKit->meals);
        $this->assertTrue($orderedKit->meals->contains(fn ($meal) => $meal->pivot->servings === 2));
        $this->assertTrue($orderedKit->meals->contains(fn ($meal) => $meal->pivot->servings === 3));
        $this->assertTrue($orderedKit->meals->contains(fn ($meal) => $meal->pivot->servings === 4));
        $this->assertTrue($orderedKit->meals->contains(fn ($meal) => $meal->pivot->servings === 5));


    }
}
