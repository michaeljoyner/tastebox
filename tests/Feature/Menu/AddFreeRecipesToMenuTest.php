<?php

namespace Tests\Feature\Menu;

use App\Meals\Meal;
use App\Orders\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AddFreeRecipesToMenuTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function add_free_recipes_to_a_menu()
    {
        $this->withoutExceptionHandling();

        $menu = factory(Menu::class)->create();

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/menus/{$menu->id}/free-recipes", [
            'meal_ids' => [$mealA->id, $mealB->id, $mealC->id],
        ]);
        $response->assertSuccessful();

        $this->assertCount(3, $menu->fresh()->freeRecipeMeals);

        $freeRecipes = $menu->fresh()->freeRecipeMeals;
        $this->assertTrue($freeRecipes->contains(fn ($meal) => $meal->meal_id === $mealA->id));
        $this->assertTrue($freeRecipes->contains(fn ($meal) => $meal->meal_id === $mealB->id));
        $this->assertTrue($freeRecipes->contains(fn ($meal) => $meal->meal_id === $mealC->id));


    }

    /**
     *@test
     */
    public function the_meal_ids_are_required_as_an_array()
    {
        $menu = factory(Menu::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/menus/{$menu->id}/free-recipes", [
            'meal_ids' => 'not-an-array',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('meal_ids');
    }

    /**
     *@test
     */
    public function each_meal_id_must_exist_on_the_meals_table()
    {
        $menu = factory(Menu::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/menus/{$menu->id}/free-recipes", [
            'meal_ids' => [999],
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('meal_ids.0');
    }
}
