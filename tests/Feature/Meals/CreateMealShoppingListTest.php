<?php

namespace Tests\Feature\Meals;

use App\Meals\Meal;
use App\Meals\MealShoppingList;
use App\MealShoppingListEntry;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class CreateMealShoppingListTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     *@test
     */
    public function create_a_new_meal_shopping_list()
    {
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();

        $response = $this->asAdmin()->post("/admin/api/meal-shopping-lists", [
            'meals' => [
                ['meal_id' => $mealA->id, 'qty' => 5],
                ['meal_id' => $mealB->id, 'qty' => 7],
                ['meal_id' => $mealC->id, 'qty' => 3],
            ]
        ]);
        $response->assertSuccessful();

        $this->assertCount(1, MealShoppingList::all());
        $list = MealShoppingList::first();

        $this->assertCount(3, $list->entries);

        $this->assertTrue(
            $list->entries->contains(
                fn (MealShoppingListEntry $entry) => $entry->meal_id === $mealA->id && $entry->servings === 5
            )
        );
    }

    /**
     *@test
     */
    public function meals_is_required_as_array()
    {
        $response = $this->asAdmin()->postJson("/admin/api/meal-shopping-lists", [
            'meals' => null
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('meals');

        $response = $this->asAdmin()->postJson("/admin/api/meal-shopping-lists", [
            'meals' => 'not-an-array'
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('meals');
    }

    /**
     *@test
     */
    public function each_meals_item_must_be_an_array()
    {
        $response = $this->asAdmin()->postJson("/admin/api/meal-shopping-lists", [
            'meals' => ['not-an-array']
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('meals.0');
    }

    /**
     *@test
     */
    public function each_meal_item_must_have_valid_meal_id()
    {
        $response = $this->asAdmin()->postJson("/admin/api/meal-shopping-lists", [
            'meals' => [
                ['meal_id' => 999, 'qty' => 1]
            ]
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('meals.0.meal_id');
    }

    /**
     *@test
     */
    public function each_meal_item_must_have_non_zero_integer_for_qty()
    {
        $response = $this->asAdmin()->postJson("/admin/api/meal-shopping-lists", [
            'meals' => [
                ['meal_id' => 999, 'qty' => 1]
            ]
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('meals.0.meal_id');
    }
}
