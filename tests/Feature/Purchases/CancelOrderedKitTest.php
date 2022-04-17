<?php

namespace Tests\Feature\Purchases;

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

class CancelOrderedKitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function cancel_an_ordered_kit()
    {
        $this->withoutExceptionHandling();

        $menu = factory(Menu::class)->create();

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();


        $menu->setMeals([
            $mealA->id,
            $mealB->id,
            $mealC->id,
        ]);

        $kits_meals = collect([
            ['id' => $mealA->id, 'servings' => 2],
            ['id' => $mealB->id, 'servings' => 2],
            ['id' => $mealC->id, 'servings' => 2],
        ]);

        $member = factory(User::class)->state('member')->create();
        $member_profile = factory(MemberProfile::class)->create(['user_id' => $member->id]);
        $order = factory(Order::class)->create(['user_id' => $member->id]);

        $kit = new Kit($menu->id, $kits_meals);
        $ordered_kit = $order->addKit($kit, Address::fake());

        $response = $this->asAdmin()->postJson("/admin/api/cancelled-kits", [
            'kit_id' => $ordered_kit->id,
            'reason' => 'test reason'
        ]);
        $response->assertSuccessful();

        $ordered_kit->refresh();

        $this->assertCount(0, $ordered_kit->meals);

        $expected_meal_summary = [];

        $this->assertSame($expected_meal_summary, $ordered_kit->meal_summary);

        $this->assertCount(1, Adjustment::all());
        $adjustment = Adjustment::first();

        $this->assertTrue($adjustment->order->is($order));
        $this->assertSame("test reason", $adjustment->reason);
        $this->assertSame(-(6 * Meal::SERVING_PRICE) * 100, $adjustment->value_in_cents);
        $this->assertSame(Adjustment::STATUS_UNRESOLVED, $adjustment->status);
        $this->assertSame($member->id, $adjustment->user_id);
        $this->assertSame($member_profile->full_name, $adjustment->customer_name);
        $this->assertSame($member_profile->email, $adjustment->customer_email);
        $this->assertSame($member_profile->phone, $adjustment->customer_phone);
    }
}
