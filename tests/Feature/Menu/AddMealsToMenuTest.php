<?php

namespace Tests\Feature\Menu;

use App\Meals\Meal;
use App\Orders\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AddMealsToMenuTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function add_meals_to_a_menu()
    {
        $this->withoutExceptionHandling();

        $menu = factory(Menu::class)->create();
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/menus/{$menu->id}/meals", [
            'meal_ids' => [$mealA->id, $mealB->id],
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('meal_menu', [
            'meal_id' => $mealA->id,
            'menu_id' => $menu->id,
        ]);

        $this->assertDatabaseHas('meal_menu', [
            'meal_id' => $mealB->id,
            'menu_id' => $menu->id,
        ]);

        $this->assertCount(2, $menu->meals);
    }

    /**
     *@test
     */
    public function the_meal_ids_are_required()
    {
        $menu = factory(Menu::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/menus/{$menu->id}/meals", []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('meal_ids');
    }

    /**
     *@test
     */
    public function the_meal_ids_must_be_an_array()
    {
        $menu = factory(Menu::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/menus/{$menu->id}/meals", [
            'meal_ids' => 'not-an-array',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('meal_ids');
    }

    /**
     *@test
     */
    public function the_meal_ids_can_be_an_empty_array()
    {
        $menu = factory(Menu::class)->create();
        $meal = factory(Meal::class)->create();
        $menu->setMeals([$meal->id]);

        $response = $this->asAdmin()->postJson("/admin/api/menus/{$menu->id}/meals", [
            'meal_ids' => [],
        ]);
        $response->assertSuccessful();

        $this->assertCount(0, $menu->fresh()->meals);
    }

    /**
     *@test
     */
    public function the_meal_ids_must_exist_in_the_meal_table()
    {
        $menu = factory(Menu::class)->create();
        $meal = factory(Meal::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/menus/{$menu->id}/meals", [
            'meal_ids' => [$meal->id, 999],
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('meal_ids.1');
    }
}
