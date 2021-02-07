<?php


namespace Tests\Feature\Purchases;


use App\Meals\Meal;
use App\Orders\Menu;
use App\Purchases\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AddManualOrderToBatchKitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_add_a_manual_order_to_the_current_batch()
    {
        $this->withoutExceptionHandling();

        $menu = factory(Menu::class)->state('current')->create();
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();

        $menu->setMeals([$mealA->id, $mealB->id, $mealC->id]);

        $response = $this->asAdmin()->postJson("/admin/api/current-batch/manual-orders", [
            'first_name' => 'bob',
            'last_name'  => 'testy',
            'email'      => 'test@test.test',
            'phone'      => '0821234567',
            'line_one'   => 'test address',
            'line_two'   => 'test address line two',
            'city'       => 'test city',
            'meals'      => [
                ['id' => $mealA->id, 'servings' => 2],
                ['id' => $mealB->id, 'servings' => 2],
                ['id' => $mealC->id, 'servings' => 2],
            ]
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('orders', [
            'first_name'     => 'bob',
            'last_name'      => 'testy',
            'email'          => 'test@test.test',
            'phone'          => '0821234567',
            'is_paid'        => true,
            'status'         => Order::STATUS_OPEN,
            'price_in_cents' => Meal::SERVING_PRICE * 2 * 3 * 100,
        ]);

        $this->assertCount(1, Order::all());
        $order = Order::first();

        $this->assertDatabaseHas('ordered_kits', [
            'order_id' => $order->id,
            'menu_id'  => $menu->id,
            'line_one' => 'test address',
            'line_two' => 'test address line two',
            'city'     => 'test city',
        ]);
    }

    /**
     * @test
     */
    public function the_first_name_is_required()
    {
        $this->assertFieldIsInvalid(['first_name' => '']);
    }

    /**
     * @test
     */
    public function the_last_name_is_required()
    {
        $this->assertFieldIsInvalid(['last_name' => '']);
    }

    /**
     * @test
     */
    public function the_email_is_required_as_an_email()
    {
        $this->assertFieldIsInvalid(['email' => '']);
        $this->assertFieldIsInvalid(['email' => 'not-valid-email']);
    }

    /**
     * @test
     */
    public function address_line_one_is_required()
    {
        $this->assertFieldIsInvalid(['line_one' => null]);
    }

    /**
     * @test
     */
    public function the_city_is_required()
    {
        $this->assertFieldIsInvalid(['city' => '']);
    }

    /**
     * @test
     */
    public function meals_is_required_as_a_non_empty_array()
    {
        $this->assertFieldIsInvalid(['meals' => null]);
        $this->assertFieldIsInvalid(['meals' => 'not-array']);
        $this->assertFieldIsInvalid(['meals' => []]);
    }

    /**
     * @test
     */
    public function meal_id_is_required_for_each_meal_and_should_exist()
    {
        $this->assertNull(Meal::find(99));
        $this->assertFieldIsInvalid(['meals' => [['id' => null, 'servings' => 2]]], 'meals.0.id');
        $this->assertFieldIsInvalid(['meals' => [['id' => 'not-integer', 'servings' => 2]]], 'meals.0.id');
        $this->assertFieldIsInvalid(['meals' => [['id' => 99, 'servings' => 2]]], 'meals.0.id');
    }

    /**
     *@test
     */
    public function the_servings_for_each_meal_is_required_as_an_integer_of_1_2_or_4()
    {
        $meal = factory(Meal::class)->create();

        $this->assertFieldIsInvalid([
            'meals' => [['id' => $meal->id, 'servings' => null]]
        ], 'meals.0.servings');
        $this->assertFieldIsInvalid([
            'meals' => [['id' => $meal->id, 'servings' => 'not-integer']]
        ], 'meals.0.servings');
        $this->assertFieldIsInvalid([
            'meals' => [['id' => $meal->id, 'servings' => 7]]
        ], 'meals.0.servings');
    }

    private function assertFieldIsInvalid($field, $error_key = null)
    {
        $menu = factory(Menu::class)->state('current')->create();
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();

        $menu->setMeals([$mealA->id, $mealB->id, $mealC->id]);

        $valid = [
            'first_name' => 'bob',
            'last_name'  => 'testy',
            'email'      => 'test@test.test',
            'phone'      => '0821234567',
            'line_one'   => 'test address',
            'line_two'   => 'test address line two',
            'city'       => 'test city',
            'meals'      => [
                ['id' => $mealA->id, 'servings' => 3],
                ['id' => $mealB->id, 'servings' => 3],
                ['id' => $mealC->id, 'servings' => 3],
            ]
        ];

        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/current-batch/manual-orders", array_merge($valid, $field));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors($error_key ?? array_key_first($field));
    }
}
