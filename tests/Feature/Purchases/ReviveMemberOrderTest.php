<?php

namespace Tests\Feature\Purchases;

use App\Meals\Meal;
use App\Memberships\MemberProfile;
use App\Orders\Menu;
use App\Purchases\KitMealSummary;
use App\Purchases\Order;
use App\Purchases\OrderedKit;
use App\Purchases\ShoppingBasket;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviveMemberOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function restore_an_abandoned_member_order()
    {
        $member = factory(MemberProfile::class)->create();
        $menu = factory(Menu::class)->state('current')->create();
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();

        $meals = new KitMealSummary(
            meals: collect([
                ['id' => $mealA->id, 'servings' => 2],
                ['id' => $mealB->id, 'servings' => 3],
                ['id' => $mealC->id, 'servings' => 4],
            ])
        );

        $order = factory(Order::class)->create(['user_id' => $member->id]);
        $ordered_kit = factory(OrderedKit::class)->create(['order_id' => $order->id, 'menu_id' => $menu->id]);
        $ordered_kit->setMeals($meals);

        $response = $this->actingAs($member->user)->get("/me/revived-orders/{$order->order_key}");
        $response->assertRedirect("/basket");

        $basket = ShoppingBasket::for($member->user);

        $this->assertCount(1, $basket->kits);
        $basket_kit = $basket->kits->first();

        $this->assertCount(3, $basket_kit->meals);
        $this->assertSame($ordered_kit->deliveryAddress()->area, $basket_kit->delivery_address->area);
        $this->assertSame($ordered_kit->deliveryAddress()->address, $basket_kit->delivery_address->address);
    }
}
