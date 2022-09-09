<?php

namespace Tests\Feature\Purchases;

use App\DeliveryArea;
use App\Meals\Meal;
use App\Orders\Menu;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function cannot_go_to_checkout_page_if_eligible_kits_missing_delivery_address()
    {
        $basket = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->state('current')->create([
            'can_order'  => true,
            'current_to' => Carbon::tomorrow()
        ]);
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();

        $menu->setMeals([$mealA->id, $mealB->id, $mealC->id]);
        $kit = $basket->addKit($menu->id);
        $kit->setMeal($mealA->id, 2);
        $kit->setMeal($mealB->id, 3);
        $kit->setMeal($mealC->id, 4);
        $this->assertSame(DeliveryArea::NOT_SET, $kit->delivery_address->area);

        $response = $this->asGuest()->get("/checkout");
        $response->assertRedirect("/basket");
    }
}
