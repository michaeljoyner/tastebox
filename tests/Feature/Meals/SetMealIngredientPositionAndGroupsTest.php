<?php


namespace Tests\Feature\Meals;


use App\Meals\Ingredient;
use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class SetMealIngredientPositionAndGroupsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function set_the_meals_ingredients_positions_and_groups()
    {
        $this->withoutExceptionHandling();

        $meal = factory(Meal::class)->create();

        $ingredientA = factory(Ingredient::class)->create();
        $ingredientB = factory(Ingredient::class)->create();
        $ingredientC = factory(Ingredient::class)->create();
        $ingredientD = factory(Ingredient::class)->create();

        $meal->ingredients()->sync([
            $ingredientA->id => ['in_kit' => true, 'quantity' => 'a bunch'],
            $ingredientB->id => ['in_kit' => true, 'quantity' => 'a bunch'],
            $ingredientC->id => ['in_kit' => true, 'quantity' => 'a bunch'],
            $ingredientD->id => ['in_kit' => true, 'quantity' => 'a bunch'],
        ]);

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/organise-ingredients", [
            'ingredients' => [
                ['id' => $ingredientA->id, 'position' => 3, 'group' => 'main dish'],
                ['id' => $ingredientB->id, 'position' => 1, 'group' => 'main dish'],
                ['id' => $ingredientC->id, 'position' => 0, 'group' => 'sauce'],
                ['id' => $ingredientD->id, 'position' => 2, 'group' => null],
            ],
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('ingredient_meal', [
            'ingredient_id' => $ingredientA->id,
            'meal_id' => $meal->id,
            'position' => 3,
            'group' => 'main dish'
        ]);

        $this->assertDatabaseHas('ingredient_meal', [
            'ingredient_id' => $ingredientB->id,
            'meal_id' => $meal->id,
            'position' => 1,
            'group' => 'main dish'
        ]);

        $this->assertDatabaseHas('ingredient_meal', [
            'ingredient_id' => $ingredientC->id,
            'meal_id' => $meal->id,
            'position' => 0,
            'group' => 'sauce'
        ]);

        $this->assertDatabaseHas('ingredient_meal', [
            'ingredient_id' => $ingredientD->id,
            'meal_id' => $meal->id,
            'position' => 2,
            'group' => null
        ]);

    }

    /**
     *@test
     */
    public function the_ingredients_are_required_as_array()
    {
        $meal = factory(Meal::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/organise-ingredients", [
            'ingredients' => null
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('ingredients');

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/organise-ingredients", [
            'ingredients' => 'not-an-array'
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('ingredients');
    }

    /**
     *@test
     */
    public function the_ingredient_id_must_exist_in_ingredient_meal_table()
    {
        $meal = factory(Meal::class)->create();


        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/organise-ingredients", [
            'ingredients' => [
                ['id' => 99, 'position' => 3, 'group' => 'main dish'],
            ],
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('ingredients.0.id');
    }

    /**
     *@test
     */
    public function the_position_must_be_an_integer()
    {
        $meal = factory(Meal::class)->create();

        $ingredient = factory(Ingredient::class)->create();

        $meal->ingredients()->sync([
            $ingredient->id => ['in_kit' => true, 'quantity' => 'a bunch'],
        ]);

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/organise-ingredients", [
            'ingredients' => [
                ['id' => $ingredient->id, 'position' => 'not-an-int', 'group' => null],
            ],
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('ingredients.0.position');
    }
}
