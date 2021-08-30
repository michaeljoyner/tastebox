<?php


namespace Tests\Feature\Purchases;


use App\Meals\Meal;
use App\Memberships\MemberProfile;
use App\Orders\Menu;
use App\Purchases\Discount;
use App\Purchases\DiscountCode;
use App\Purchases\MemberDiscount;
use App\Purchases\Order;
use App\Purchases\OrderedKit;
use App\Purchases\ShoppingBasket;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class MemberPlaceOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function place_an_order_as_a_member()
    {
        $this->withoutExceptionHandling();

        $profile = factory(MemberProfile::class)->create();

        $discount_code = factory(MemberDiscount::class)->create([
            'type'  => Discount::LUMP,
            'value' => 50
        ]);

        $menuA = factory(Menu::class)->state('current')->create([
            'can_order'  => true,
            'current_to' => Carbon::tomorrow()
        ]);
        $menuB = factory(Menu::class)->state('upcoming')->create([
            'can_order' => true,
        ]);

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $mealD = factory(Meal::class)->create();
        $mealE = factory(Meal::class)->create();
        $mealF = factory(Meal::class)->create();

        $menuA->setMeals([$mealA->id, $mealB->id, $mealC->id]);
        $menuB->setMeals([$mealD->id, $mealE->id, $mealF->id]);

        $basket = ShoppingBasket::for($profile->user);

        $kitA = $basket->addKit($menuA->id);
        $basket->addMealToKit($kitA->id, $mealA->id, 2);
        $basket->addMealToKit($kitA->id, $mealB->id, 3);
        $basket->addMealToKit($kitA->id, $mealC->id, 4);

        $kitB = $basket->addKit($menuB->id);
        $basket->addMealToKit($kitB->id, $mealD->id, 3);
        $basket->addMealToKit($kitB->id, $mealE->id, 4);
        $basket->addMealToKit($kitB->id, $mealF->id, 5);



        $response = $this->actingAs($profile->user)->post("/checkout", [
            'member_discount_id' => $discount_code->id,
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('orders', [
            'user_id'        => $profile->user->id,
            'first_name'     => $profile->first_name,
            'last_name'      => $profile->last_name,
            'email'          => $profile->email,
            'phone'          => $profile->phone,
            'price_in_cents' => ((21 * Meal::SERVING_PRICE) - 50) * 100,
            'discount_code'  => $discount_code->code,
            'discount_type'  => Discount::LUMP,
            'discount_value' => 50,
            'is_paid'        => false,
            'status'         => Order::STATUS_CREATED,
        ]);

        $this->assertDeleted($discount_code);

        $order = Order::where('email', $profile->email)->first();

        $this->assertDatabaseHas('ordered_kits', [
            'order_id'         => $order->id,
            'kit_id'           => $kitA->id,
            'menu_id'          => $menuA->id,
            'delivery_date'    => $menuA->delivery_from->format("Y-m-d"),
            'menu_week_number' => $menuA->current_from->week,
            'line_one'         => $profile->address_line_one,
            'line_two'         => $profile->address_line_two,
            'city'             => $profile->address_city,
            'postal_code'      => '',
            'delivery_notes'   => '',
            'meal_summary'     => $this->asJson([
                ['id' => $mealA->id, 'name' => $mealA->name, 'servings' => 2],
                ['id' => $mealB->id, 'name' => $mealB->name, 'servings' => 3],
                ['id' => $mealC->id, 'name' => $mealC->name, 'servings' => 4],
            ], 'meal_summary'),
        ]);

        $this->assertDatabaseHas('ordered_kits', [
            'order_id'         => $order->id,
            'kit_id'           => $kitB->id,
            'menu_id'          => $menuB->id,
            'delivery_date'    => $menuB->delivery_from->format('Y-m-d'),
            'menu_week_number' => $menuB->current_from->week,
            'line_one'         => $profile->address_line_one,
            'line_two'         => $profile->address_line_two,
            'city'             => $profile->address_city,
            'postal_code'      => '',
            'delivery_notes'   => '',
            'meal_summary'     => $this->asJson([
                ['id' => $mealD->id, 'name' => $mealD->name, 'servings' => 3],
                ['id' => $mealE->id, 'name' => $mealE->name, 'servings' => 4],
                ['id' => $mealF->id, 'name' => $mealF->name, 'servings' => 5],
            ], 'meal_summary'),
        ]);

        $ordered_kitA = OrderedKit::where('kit_id', $kitA->id)->first();
        $ordered_kitB = OrderedKit::where('kit_id', $kitB->id)->first();

        $this->assertDatabaseHas('meal_ordered_kit', [
            'ordered_kit_id' => $ordered_kitA->id,
            'meal_id'        => $mealA->id,
            'servings'       => 2
        ]);
        $this->assertDatabaseHas('meal_ordered_kit', [
            'ordered_kit_id' => $ordered_kitA->id,
            'meal_id'        => $mealB->id,
            'servings'       => 3
        ]);
        $this->assertDatabaseHas('meal_ordered_kit', [
            'ordered_kit_id' => $ordered_kitA->id,
            'meal_id'        => $mealC->id,
            'servings'       => 4
        ]);
        $this->assertDatabaseHas('meal_ordered_kit', [
            'ordered_kit_id' => $ordered_kitB->id,
            'meal_id'        => $mealD->id,
            'servings'       => 3
        ]);
        $this->assertDatabaseHas('meal_ordered_kit', [
            'ordered_kit_id' => $ordered_kitB->id,
            'meal_id'        => $mealE->id,
            'servings'       => 4
        ]);
        $this->assertDatabaseHas('meal_ordered_kit', [
            'ordered_kit_id' => $ordered_kitB->id,
            'meal_id'        => $mealF->id,
            'servings'       => 5
        ]);
    }
}
