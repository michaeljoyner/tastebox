<?php


namespace Tests\Feature\Purchases;


use App\DeliveryAddress;
use App\DeliveryArea;
use App\Meals\Meal;
use App\Orders\Menu;
use App\Purchases\Discount;
use App\Purchases\DiscountCode;
use App\Purchases\Kit;
use App\Purchases\Order;
use App\Purchases\OrderedKit;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PlaceOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function place_an_order()
    {
        $this->withoutExceptionHandling();

        $discount_code = factory(DiscountCode::class)->create([
            'uses'  => 5,
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

        $basket = ShoppingBasket::for(null);

        $kitA = $basket->addKit($menuA->id);
        $kitA->setMeal($mealA->id, 2);
        $kitA->setMeal($mealB->id, 3);
        $kitA->setMeal($mealC->id, 4);

        $kitB = $basket->addKit($menuB->id);
        $kitB->setMeal($mealD->id, 3);
        $kitB->setMeal($mealE->id, 4);
        $kitB->setMeal($mealF->id, 5);

        $test_addressA = new DeliveryAddress(DeliveryArea::HOWICK, '123 test street');
        $test_addressB = new DeliveryAddress(DeliveryArea::HILTON, '456 test street');

        $kitA->setDeliveryAddress($test_addressA);
        $kitB->setDeliveryAddress($test_addressB);

        $response = $this->asGuest()->post("/checkout", [
            'first_name'              => 'test first name',
            'last_name'               => 'test last name',
            'email'                   => 'test@test.test',
            'phone'                   => '0798888888',
            'discount_code'           => $discount_code->code,
            'subscribe_to_newsletter' => true,
            'get_sms_reminder'       => true,

        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('mailing_list_members', [
            'name'  => 'test first name test last name',
            'email' => 'test@test.test',
        ]);

        $this->assertDatabaseHas('sms_reminder_subscribers', [
            'name'        => 'test first name',
            'cell_number' => '27798888888',
        ]);


        $this->assertDatabaseHas('orders', [
            'first_name'     => 'test first name',
            'last_name'      => 'test last name',
            'email'          => 'test@test.test',
            'phone'          => '0798888888',
            'price_in_cents' => ((21 * Meal::SERVING_PRICE) - 50) * 100,
            'discount_code'  => $discount_code->code,
            'discount_type'  => Discount::LUMP,
            'discount_value' => 50,
            'is_paid'        => false,
            'status' => Order::STATUS_CREATED,
        ]);

        $this->assertDatabaseHas('discount_codes', [
            'id'   => $discount_code->id,
            'uses' => 4
        ]);

        $order = Order::where('email', 'test@test.test')->first();

        $this->assertDatabaseHas('ordered_kits', [
            'order_id'         => $order->id,
            'kit_id'           => $kitA->id,
            'menu_id'          => $menuA->id,
            'delivery_date'    => $menuA->delivery_from->format("Y-m-d"),
            'menu_week_number' => $menuA->current_from->week,
            'line_one'         => $test_addressA->address,
            'line_two'         => '',
            'city'             => $test_addressA->area->value,
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
            'line_one'         => $test_addressB->address,
            'line_two'         => '',
            'city'             => $test_addressB->area->value,
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

    /**
     * @test
     */
    public function place_an_order_without_discount_code()
    {
        $this->withoutExceptionHandling();

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

        $basket = ShoppingBasket::for(null);

        $kitA = $basket->addKit($menuA->id);
        $kitA->setMeal($mealA->id, 2);
        $kitA->setMeal($mealB->id, 3);
        $kitA->setMeal($mealC->id, 4);

        $kitB = $basket->addKit($menuB->id);
        $kitB->setMeal($mealD->id, 3);
        $kitB->setMeal($mealE->id, 4);
        $kitB->setMeal($mealF->id, 5);

        $test_addressA = new DeliveryAddress(DeliveryArea::HOWICK, '123 test street');
        $test_addressB = new DeliveryAddress(DeliveryArea::HILTON, '456 test street');

        $kitA->setDeliveryAddress($test_addressA);
        $kitB->setDeliveryAddress($test_addressB);

        $response = $this->asGuest()->post("/checkout", [
            'first_name'    => 'test first name',
            'last_name'     => 'test last name',
            'email'         => 'test@test.test',
            'phone'         => '0798888888',
            'discount_code' => null,
        ]);
        $response->assertSuccessful();


        $this->assertDatabaseHas('orders', [
            'first_name'     => 'test first name',
            'last_name'      => 'test last name',
            'email'          => 'test@test.test',
            'phone'          => '0798888888',
            'price_in_cents' => 21 * Meal::SERVING_PRICE * 100,
            'discount_code'  => null,
            'discount_type'  => null,
            'discount_value' => null,
            'is_paid'        => false,
        ]);
    }

    /**
     * @test
     */
    public function the_discount_code_must_be_valid()
    {
        $kit = $this->setKit();

        $old_code = factory(DiscountCode::class)->state('expired')->create();
        $used_code = factory(DiscountCode::class)->state('used')->create();

        $this->assertFieldIsInvalid($kit, ['discount_code' => 'xjhdjhdndkjxhx']);
        $this->assertFieldIsInvalid($kit, ['discount_code' => $old_code->code]);
        $this->assertFieldIsInvalid($kit, ['discount_code' => $used_code->code]);
    }

    /**
     * @test
     */
    public function the_first_name_is_required_without_the_last_name()
    {
        $kit = $this->setKit();

        $this->assertFieldIsInvalid($kit, ['first_name' => null, 'last_name' => null]);
    }

    /**
     * @test
     */
    public function the_email_is_required()
    {
        $kit = $this->setKit();

        $this->assertFieldIsInvalid($kit, ['email' => null]);
    }

    /**
     * @test
     */
    public function the_email_must_be_valid()
    {
        $kit = $this->setKit();

        $this->assertFieldIsInvalid($kit, ['email' => 'not-a-real-email']);
    }




    private function assertFieldIsInvalid($kit, $field, $error_key = null)
    {

        $valid = [
            'first_name' => 'test first name',
            'last_name'  => 'test last name',
            'email'      => 'test@test.test',
            'phone'      => '0798888888',
            'delivery'   => [
                $kit->id => [
                    'line_one'    => 'test road',
                    'line_two'    => 'test district',
                    'city'        => 'test city',
                    'postal_code' => 'test code',
                    'notes'       => 'test notes',
                ],
            ],
        ];

        $response = $this
            ->asGuest()
            ->from("/checkout")
            ->post("checkout", array_merge($valid, $field));
        $response->assertRedirect("/checkout");

        $response->assertSessionHasErrors($error_key ?? array_key_first($field));


    }

    private function setKit(): Kit
    {
        $menu = factory(Menu::class)->state('current')->create([
            'can_order'  => true,
            'current_to' => Carbon::tomorrow()
        ]);

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();


        $menu->setMeals([$mealA->id, $mealB->id,]);

        $basket = ShoppingBasket::for(null);

        $kit = $basket->addKit($menu->id);
        $kit->setMeal($mealA->id, 2);
        $kit->setMeal($mealB->id, 3);

        return $kit;
    }
}
