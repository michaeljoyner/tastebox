<?php

namespace Tests\Feature\Purchases;

use App\AddOns\AddOn;
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
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateOrderedKitMealsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function update_the_meals_of_an_ordered_kit()
    {
        $this->withoutExceptionHandling();

        $menu = factory(Menu::class)->create();

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $mealD = factory(Meal::class)->create();
        $mealE = factory(Meal::class)->create();
        $mealF = factory(Meal::class)->create();
        $mealG = factory(Meal::class)->create();
        $mealH = factory(Meal::class)->create();
        $addOnA = factory(AddOn::class)->create(['price' => 1000]);
        $addOnB = factory(AddOn::class)->create(['price' => 2000]);
        $addOnC = factory(AddOn::class)->create(['price' => 3000]);

        $menu->setMeals([
            $mealA->id,
            $mealB->id,
            $mealC->id,
            $mealD->id,
            $mealE->id,
            $mealF->id,
            $mealG->id,
            $mealH->id,
        ]);
        $menu->addOns()->sync([$addOnA->id, $addOnB->id, $addOnC->id]);

        $original_meals = collect([
            ['id' => $mealA->id, 'servings' => 2],
            ['id' => $mealB->id, 'servings' => 2],
            ['id' => $mealC->id, 'servings' => 2],
        ]);
        //original value of add-ons 6000
        $originalAddOns = collect([
            ['key' => $addOnA->uuid, 'id' => $addOnA->id, 'name' => $addOnA->name, 'price' => $addOnA->price, 'qty' => 2],
            ['key' => $addOnB->uuid, 'id' => $addOnB->id, 'name' => $addOnB->name, 'price' => $addOnB->price, 'qty' => 2],
        ]);

        $member = factory(User::class)->state('member')->create();
        $member_profile = factory(MemberProfile::class)->create(['user_id' => $member->id]);
        $order = factory(Order::class)->create(['user_id' => $member->id]);

        $kit = new Kit($menu->id, $original_meals, $originalAddOns, DeliveryAddress::fake());
        $ordered_kit = $order->addKit($kit);

        $response = $this->asAdmin()->postJson("/admin/api/ordered-kits/{$ordered_kit->id}", [
            'meals' => [
                ['id' => $mealE->id, 'servings' => 3],
                ['id' => $mealF->id, 'servings' => 4],
                ['id' => $mealG->id, 'servings' => 5],
                ['id' => $mealH->id, 'servings' => 2],
            ],
            //updated value of add-ons 15000
            'add_ons' => [
                ['id' => $addOnA->id, 'qty' => 3],
                ['id' => $addOnC->id, 'qty' => 4],
            ],
            'reason' => 'test reason'
        ]);
        $response->assertSuccessful();

        $ordered_kit->refresh();

        $this->assertCount(4, $ordered_kit->meals);
        $this->assertTrue($ordered_kit->meals->contains(fn ($m) => $m->id === $mealE->id));
        $this->assertTrue($ordered_kit->meals->contains(fn ($m) => $m->id === $mealF->id));
        $this->assertTrue($ordered_kit->meals->contains(fn ($m) => $m->id === $mealG->id));
        $this->assertTrue($ordered_kit->meals->contains(fn ($m) => $m->id === $mealH->id));

        $this->assertFalse($ordered_kit->meals->contains(fn ($m) => $m->id === $mealA->id));
        $this->assertFalse($ordered_kit->meals->contains(fn ($m) => $m->id === $mealB->id));
        $this->assertFalse($ordered_kit->meals->contains(fn ($m) => $m->id === $mealC->id));

        $this->assertTrue($ordered_kit->addOns->contains(fn (AddOn $addOn) => ($addOn->id === $addOnA->id) && $addOn->pivot->qty === 3));
        $this->assertTrue($ordered_kit->addOns->contains(fn (AddOn $addOn) => ($addOn->id === $addOnC->id) && $addOn->pivot->qty === 4));
        $this->assertFalse($ordered_kit->addOns->contains($addOnB));

        $expected_meal_summary = [
            ['id' => $mealE->id, 'name' => $mealE->name, 'servings' => 3],
            ['id' => $mealF->id, 'name' => $mealF->name, 'servings' => 4],
            ['id' => $mealG->id, 'name' => $mealG->name, 'servings' => 5],
            ['id' => $mealH->id, 'name' => $mealH->name, 'servings' => 2],
        ];

        $this->assertSame($expected_meal_summary, $ordered_kit->meal_summary);

        $this->assertCount(1, Adjustment::all());
        $adjustment = Adjustment::first();

        $this->assertTrue($adjustment->order->is($order));
        $this->assertSame('test reason', $adjustment->reason);
        $this->assertSame((((14 - 6) * Meal::SERVING_PRICE) * 100) + 9000, $adjustment->value_in_cents);
        $this->assertSame(Adjustment::STATUS_UNRESOLVED, $adjustment->status);
        $this->assertSame($member->id, $adjustment->user_id);
        $this->assertSame($member_profile->full_name, $adjustment->customer_name);
        $this->assertSame($member_profile->email, $adjustment->customer_email);
        $this->assertSame($member_profile->phone, $adjustment->customer_phone);

        $this->assertCount(1, $ordered_kit->activityLogs()->get());
    }

    /**
     *@test
     */
    public function the_meals_are_required_as_an_array()
    {
        $this->assertFieldIsInvalid(['meals' => null]);
        $this->assertFieldIsInvalid(['meals' => 'not-an-array']);
    }

    /**
     *@test
     */
    public function the_reason_is_required()
    {
        $this->assertFieldIsInvalid(['reason' => null]);
    }

    /**
     *@test
     */
    public function each_meal_should_be_an_array()
    {
        $this->assertFieldIsInvalid(['meals' => ['not-an-array-value']], 'meals.0');
    }

    /**
     *@test
     */
    public function each_meal_should_have_an_id_of_a_menu_meal()
    {
        $not_on_menu = factory(Meal::class)->create();
        $this->assertFieldIsInvalid(['meals' => [
            ['id' => null, 'servings' => 2],
        ]], 'meals.0.id');

        $this->assertFieldIsInvalid(['meals' => [
            ['id' => $not_on_menu->id, 'servings' => 2],
        ]], 'meals.0.id');
    }

    /**
     *@test
     */
    public function each_meal_requires_a_numeric_serving_greater_than_zero()
    {
        $meal = factory(Meal::class)->create();

        $this->assertFieldIsInvalid(['meals' => [
            ['id' => $meal->id, 'servings' => null],
        ]], 'meals.0.servings', [$meal->id]);

        $this->assertFieldIsInvalid(['meals' => [
            ['id' => $meal->id, 'servings' => 'not-a-number'],
        ]], 'meals.0.servings', [$meal->id]);

        $this->assertFieldIsInvalid(['meals' => [
            ['id' => $meal->id, 'servings' => 0],
        ]], 'meals.0.servings', [$meal->id]);
    }

    private function assertFieldIsInvalid(array $field, $error_key = null,  $include_meals = [])
    {
        $menu = factory(Menu::class)->create();

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $mealD = factory(Meal::class)->create();
        $mealE = factory(Meal::class)->create();
        $mealF = factory(Meal::class)->create();
        $mealG = factory(Meal::class)->create();
        $mealH = factory(Meal::class)->create();

        $meal_ids = [
            $mealA->id,
            $mealB->id,
            $mealC->id,
            $mealD->id,
            $mealE->id,
            $mealF->id,
            $mealG->id,
            $mealH->id,
        ];

        $menu->setMeals(array_merge($meal_ids, $include_meals));

        $original_meals = collect([
            ['id' => $mealA->id, 'servings' => 2],
            ['id' => $mealB->id, 'servings' => 2],
            ['id' => $mealC->id, 'servings' => 2],
        ]);

        $order = factory(Order::class)->create();

        $kit = new Kit($menu->id, $original_meals, collect([]), DeliveryAddress::fake());
        $ordered_kit = $order->addKit($kit);

        $valid = [
            'meals' => [
                ['id' => $mealE->id, 'servings' => 3],
                ['id' => $mealF->id, 'servings' => 4],
                ['id' => $mealG->id, 'servings' => 5],
                ['id' => $mealH->id, 'servings' => 2],
            ],
            'reason' => 'test reason'
        ];

        $response = $this->asAdmin()
                         ->postJson(
                             "/admin/api/ordered-kits/{$ordered_kit->id}",
                             array_merge($valid, $field)
                         );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors($error_key ?? array_key_first($field));
    }
}
