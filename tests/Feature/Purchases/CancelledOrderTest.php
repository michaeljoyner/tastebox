<?php


namespace Tests\Feature\Purchases;


use App\Meals\Meal;
use App\Orders\Menu;
use App\Purchases\Address;
use App\Purchases\Kit;
use App\Purchases\NullDiscount;
use App\Purchases\Order;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CancelledOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function payment_is_cancelled()
    {
        $menu = factory(Menu::class)->create();
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $menu->setMeals([$mealA->id, $mealB->id]);

        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);
        $kit->setMeal($mealA, 2);
        $kit->setMeal($mealB, 3);

        $customer = [
            'first_name' => 'test first name',
            'last_name' => 'test last name',
            'phone' => '0791112223',
            'email' => 'test@test.test',
        ];

        $order = Order::makeNew($customer, $basket->kits, new NullDiscount());

        $response = $this->get("/payfast/cancel/{$order->order_key}");

        $response->assertRedirect("/checkout");
        $this->assertCount(1, $basket->kits);
    }
}
