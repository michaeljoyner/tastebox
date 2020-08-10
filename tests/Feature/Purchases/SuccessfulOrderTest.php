<?php


namespace Tests\Feature\Purchases;


use App\Meals\Meal;
use App\Orders\Menu;
use App\Purchases\Address;
use App\Purchases\Kit;
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
        $kit->setMeal($mealA->id, 2);
        $kit->setMeal($mealB->id, 3);

        $order = Order::makeNew([
            'first_name' => 'Test',
            'last_name'  => 'Name',
            'email'      => 'tst@test.test',
            'phone'      => 'test phone',
        ], 100);

        $basket->kits->each(fn(Kit $kit) => $order->addKit($kit, new Address([
            'line_one'    => '123 Sesame',
            'city'        => 'New York',
            'postal_code' => '555',
        ])));

        $response = $this->get("/payfast/return/{$order->order_key}");

        $response->assertRedirect("/thank-you/$order->order_key");

        $this->assertBasketEmpty();

    }

    private function assertBasketEmpty()
    {
        $basket = ShoppingBasket::for(null);
        $this->assertCount(0, $basket->kits);
    }
}
