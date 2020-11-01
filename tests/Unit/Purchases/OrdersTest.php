<?php


namespace Tests\Unit\Purchases;


use App\Meals\Meal;
use App\Orders\Menu;
use App\Purchases\Address;
use App\Purchases\Order;
use App\Purchases\OrderedKit;
use App\Purchases\Payment;
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
        $this->assertSame(Order::STATUS_PENDING, $order->status);
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

        $this->assertSame(OrderedKit::STATUS_DUE, $orderedKit->status);
        $this->assertEquals($kit->id, $orderedKit->kit_id);
        $this->assertEquals($menu->id, $orderedKit->menu_id);
        $this->assertEquals($menu->current_from->week, $orderedKit->menu_week_number);
        $this->assertTrue($menu->delivery_from->isSameDay($orderedKit->delivery_date));
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

    /**
     *@test
     */
    public function delete_order_and_its_ordered_kits()
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

        $order->fullDelete();

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
        $this->assertDatabaseMissing('ordered_kits', ['id' => $orderedKit->id]);
    }

    /**
     *@test
     */
    public function get_customer_full_name()
    {
        $normal = factory(Order::class)->create([
            'first_name' => 'Sammy',
            'last_name' => 'Snake',
        ]);

        $madonna = factory(Order::class)->create([
            'first_name' => 'Madonna',
            'last_name' => '',
        ]);

        $obama = factory(Order::class)->create([
            'first_name' => '',
            'last_name' => 'Obama',
        ]);

        $this->assertSame('Sammy Snake', $normal->customerFullname());
        $this->assertSame('Madonna', $madonna->customerFullname());
        $this->assertSame('Obama', $obama->customerFullname());
    }

    /**
     *@test
     */
    public function check_order_has_payment()
    {
        $paid = factory(Order::class)->state('paid')->create();
        $payment = factory(Payment::class)->state('payfast')->create([
            'order_id' => $paid->id,
        ]);

        $unpaid = factory(Order::class)->state('unpaid')->create();

        $this->assertTrue($paid->isPaid());
        $this->assertFalse($unpaid->isPaid());
    }

    /**
     *@test
     */
    public function order_can_give_customer()
    {
        $order = factory(Order::class)->state('paid')->create([
            'first_name' => 'Fancy',
            'last_name' => 'Pants',
            'email' => 'test@test.test',
            'phone' => 'test phone',
        ]);

        $customer = $order->customer();

        $this->assertSame('Fancy Pants', $customer->name);
        $this->assertSame('test@test.test', $customer->email);
        $this->assertSame('test phone', $customer->phone);
    }
}
