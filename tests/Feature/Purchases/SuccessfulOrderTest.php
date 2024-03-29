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

class SuccessfulOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function payfast_redirects_on_payment_success_flow()
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

        $response = $this->get("/payfast/return/{$order->order_key}");

        $response->assertRedirect("/thank-you/$order->order_key");

        $this->assertBasketEmpty();

        $this->assertSame(Order::STATUS_PENDING, $order->fresh()->status);

    }

    private function assertBasketEmpty()
    {
        $basket = ShoppingBasket::for(null);
        $this->assertCount(0, $basket->kits);
    }
}
