<?php

namespace Tests\Feature\Purchases;

use App\DeliveryAddress;
use App\Meals\Meal;
use App\Memberships\MemberProfile;
use App\Orders\Menu;
use App\Purchases\Address;
use App\Purchases\Adjustment;
use App\Purchases\Kit;
use App\Purchases\Order;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FetchUpcomingOrderedKitsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function does_not_include_kits_from_unpaid_orders()
    {
        $this->withoutExceptionHandling();

        $menu = factory(Menu::class)->state('upcoming')->create();

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();


        $menu->setMeals([
            $mealA->id,
            $mealB->id,
            $mealC->id,
        ]);

        $original_meals = collect([
            ['id' => $mealA->id, 'servings' => 2],
            ['id' => $mealB->id, 'servings' => 2],
            ['id' => $mealC->id, 'servings' => 2],
        ]);

        $order = factory(Order::class)->create(['is_paid' => false, 'status' => Order::STATUS_CREATED]);

        $kit = new Kit($menu->id, $original_meals, collect([]), DeliveryAddress::fake());
        $ordered_kit = $order->addKit($kit);

        $response = $this->asAdmin()->getJson("/admin/api/upcoming-ordered-kits");
        $response->assertSuccessful();

        $this->assertCount(0, $response->json());






    }
}
