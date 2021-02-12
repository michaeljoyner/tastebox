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
        $ingredientE = factory(Ingredient::class)->create();

        $meal->ingredients()->sync([
            $ingredientA->id => ['in_kit' => true, 'quantity' => '100g'],
            $ingredientB->id => ['in_kit' => true, 'quantity' => '100g'],
            $ingredientC->id => ['in_kit' => true, 'quantity' => '100g'],
            $ingredientD->id => ['in_kit' => true, 'quantity' => '100g'],
            $ingredientE->id => ['in_kit' => true, 'quantity' => '100g'],
        ]);

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/organise-ingredients", [
            'ingredients' => [
                ['id' => $ingredientA->id, 'position' => 4, 'group' => 'main dish', 'bundled' => false],
                ['id' => $ingredientB->id, 'position' => 2, 'group' => 'main dish', 'bundled' => false],
                ['id' => $ingredientC->id, 'position' => 0, 'group' => 'sauce', 'bundled' => true],
                ['id' => $ingredientD->id, 'position' => 3, 'group' => null, 'bundled' => false],
                ['id' => $ingredientE->id, 'position' => 1, 'group' => 'sauce', 'bundled' => true],
            ],
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('ingredient_meal', [
            'ingredient_id' => $ingredientA->id,
            'meal_id' => $meal->id,
            'position' => 4,
            'group' => 'main dish',
            'bundled' => false,
        ]);

        $this->assertDatabaseHas('ingredient_meal', [
            'ingredient_id' => $ingredientB->id,
            'meal_id' => $meal->id,
            'position' => 2,
            'group' => 'main dish',
            'bundled' => false,
        ]);

        $this->assertDatabaseHas('ingredient_meal', [
            'ingredient_id' => $ingredientC->id,
            'meal_id' => $meal->id,
            'position' => 0,
            'group' => 'sauce',
            'bundled' => true,
        ]);

        $this->assertDatabaseHas('ingredient_meal', [
            'ingredient_id' => $ingredientD->id,
            'meal_id' => $meal->id,
            'position' => 3,
            'group' => null,
            'bundled' => false,
        ]);

        $this->assertDatabaseHas('ingredient_meal', [
            'ingredient_id' => $ingredientE->id,
            'meal_id' => $meal->id,
            'position' => 1,
            'group' => 'sauce',
            'bundled' => true,
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

    /**
     *@test
     */
    public function bundled_must_be_a_bool()
    {
        $meal = factory(Meal::class)->create();

        $ingredient = factory(Ingredient::class)->create();

        $meal->ingredients()->sync([
            $ingredient->id => ['in_kit' => true, 'quantity' => 'a bunch'],
        ]);

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/organise-ingredients", [
            'ingredients' => [
                ['id' => $ingredient->id, 'position' => 1, 'group' => null, 'bundled' => 'not-a-bool'],
            ],
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('ingredients.0.bundled');
    }
}
