<?php


namespace Tests\Feature\Purchases;


use App\Meals\Meal;
use App\Orders\Menu;
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
            'type'  => DiscountCode::LUMP,
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

        $response = $this->asGuest()->post("/checkout", [
            'first_name'    => 'test first name',
            'last_name'     => 'test last name',
            'email'         => 'test@test.test',
            'phone'         => '0798888888',
            'discount_code' => $discount_code->code,
            'delivery'      => [
                $kitA->id => [
                    'line_one'    => 'test road',
                    'line_two'    => 'test district',
                    'city'        => 'test city',
                    'postal_code' => 'test code',
                    'notes'       => 'test notes',
                ],
                $kitB->id => [
                    'line_one'    => 'test road',
                    'line_two'    => 'test district',
                    'city'        => 'test city',
                    'postal_code' => 'test code',
                    'notes'       => 'test notes',
                ],
            ],
        ]);
        $response->assertSuccessful();


        $this->assertDatabaseHas('orders', [
            'first_name'     => 'test first name',
            'last_name'      => 'test last name',
            'email'          => 'test@test.test',
            'phone'          => '0798888888',
            'price_in_cents' => ((21 * Meal::SERVING_PRICE) - 50) * 100,
            'discount_code'  => $discount_code->code,
            'discount_type'  => DiscountCode::LUMP,
            'discount_value' => 50,
            'is_paid'        => false,
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
            'line_one'         => 'test road',
            'line_two'         => 'test district',
            'city'             => 'test city',
            'postal_code'      => 'test code',
            'delivery_notes'   => 'test notes',
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
            'line_one'         => 'test road',
            'line_two'         => 'test district',
            'city'             => 'test city',
            'postal_code'      => 'test code',
            'delivery_notes'   => 'test notes',
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

        $response = $this->asGuest()->post("/checkout", [
            'first_name'    => 'test first name',
            'last_name'     => 'test last name',
            'email'         => 'test@test.test',
            'phone'         => '0798888888',
            'discount_code' => null,
            'delivery'      => [
                $kitA->id => [
                    'line_one'    => 'test road',
                    'line_two'    => 'test district',
                    'city'        => 'test city',
                    'postal_code' => 'test code',
                    'notes'       => 'test notes',
                ],
                $kitB->id => [
                    'line_one'    => 'test road',
                    'line_two'    => 'test district',
                    'city'        => 'test city',
                    'postal_code' => 'test code',
                    'notes'       => 'test notes',
                ],
            ],
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
     *@test
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

    /**
     * @test
     */
    public function a_delivery_is_required()
    {
        $kit = $this->setKit();

        $this->assertFieldIsInvalid($kit, ['delivery' => null]);
    }

    /**
     * @test
     */
    public function delivery_must_be_an_array()
    {
        $kit = $this->setKit();

        $this->assertFieldIsInvalid($kit, ['delivery' => 'just-text']);
    }

    /**
     * @test
     */
    public function delivery_cannot_be_empty_array()
    {
        $kit = $this->setKit();

        $this->assertFieldIsInvalid($kit, ['delivery' => []]);
    }

    /**
     * @test
     */
    public function delivery_keys_must_match_existing_kit_id()
    {
        $kit = $this->setKit();

        $this->assertFieldIsInvalid($kit, [
            'delivery' => [
                'not-kit-id' => [
                    'line_one'    => 'test road',
                    'line_two'    => 'test district',
                    'city'        => 'test city',
                    'postal_code' => 'test code',
                    'notes'       => 'test notes',
                ]
            ]
        ], 'delivery.not-kit-id');
    }

    /**
     * @test
     */
    public function delivery_needs_line_one()
    {
        $kit = $this->setKit();

        $this->assertFieldIsInvalid($kit, [
            'delivery' => [
                $kit->id => [
                    'line_one'    => null,
                    'line_two'    => 'test district',
                    'city'        => 'test city',
                    'postal_code' => 'test code',
                    'notes'       => 'test notes',
                ]
            ]
        ], "delivery.{$kit->id}.line_one");
    }

    /**
     * @test
     */
    public function delivery_needs_city()
    {
        $kit = $this->setKit();

        $this->assertFieldIsInvalid($kit, [
            'delivery' => [
                $kit->id => [
                    'line_one'    => 'test road',
                    'line_two'    => 'test district',
                    'city'        => null,
                    'postal_code' => 'test code',
                    'notes'       => 'test notes',
                ]
            ]
        ], "delivery.{$kit->id}.city");
    }

    /**
     * @test
     */
    public function delivery_needs_postal_code()
    {
        $kit = $this->setKit();

        $this->assertFieldIsInvalid($kit, [
            'delivery' => [
                $kit->id => [
                    'line_one'    => 'test road',
                    'line_two'    => 'test district',
                    'city'        => 'test city',
                    'postal_code' => null,
                    'notes'       => 'test notes',
                ]
            ]
        ], "delivery.{$kit->id}.postal_code");
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
