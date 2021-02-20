<?php


namespace Tests\Feature\Meals;


use App\Meals\Ingredient;
use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateMealIngredientsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function update_a_meals_ingredients()
    {
        $this->withoutExceptionHandling();

        $meal = factory(Meal::class)->create();

        $ingredientA = factory(Ingredient::class)->create();
        $ingredientB = factory(Ingredient::class)->create();
        $ingredientC = factory(Ingredient::class)->create();
        $ingredientD = factory(Ingredient::class)->create();
        $ingredientE = factory(Ingredient::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/ingredients", [
            'ingredients' => [
                ['id' => $ingredientA->id, 'quantity' => '2', 'form' => 'sliced', 'in_kit' => false],
                ['id'       => $ingredientB->id,
                 'quantity' => '3 tsp',
                 'form'     => 'chopped',
                 'in_kit'   => false,
                 'group'    => 'test group',
                 'bundled'  => false
                ],
                ['id' => $ingredientC->id, 'quantity' => '1 bag', 'form' => 'rinsed', 'in_kit' => true],
                ['id' => $ingredientD->id, 'quantity' => '', 'form' => '', 'in_kit' => true],
                ['id' => $ingredientE->id, 'quantity' => null, 'form' => 'crushed', 'in_kit' => true],
                ['id' => $ingredientE->id, 'quantity' => 'a bit', 'form' => 'powdered', 'in_kit' => true],
            ],
        ]);
        $response->assertSuccessful();

        $customerIngredients = $meal->customerIngredients()->get();
        $kitIngredients = $meal->kitIngredients()->get();

        $this->assertCount(2, $customerIngredients);
        $this->assertCount(4, $kitIngredients);

        $this->assertTrue($customerIngredients->contains($ingredientA));
        $this->assertTrue($customerIngredients->contains($ingredientB));
        $this->assertTrue($kitIngredients->contains($ingredientC));
        $this->assertTrue($kitIngredients->contains($ingredientD));
        $this->assertTrue($kitIngredients->contains($ingredientE));

        $this->assertDatabaseHas('ingredient_meal', [
            'meal_id'       => $meal->id,
            'ingredient_id' => $ingredientA->id,
            'form'          => 'sliced',
            'in_kit'        => false,
            'quantity'      => '2',
        ]);

        $this->assertDatabaseHas('ingredient_meal', [
            'meal_id'       => $meal->id,
            'ingredient_id' => $ingredientB->id,
            'form'          => 'chopped',
            'in_kit'        => false,
            'quantity'      => '3 tsp',
            'group'         => 'test group',
            'bundled'       => false
        ]);

        $this->assertDatabaseHas('ingredient_meal', [
            'meal_id'       => $meal->id,
            'ingredient_id' => $ingredientC->id,
            'form'          => 'rinsed',
            'in_kit'        => true,
            'quantity'      => '1 bag',
        ]);

        $this->assertDatabaseHas('ingredient_meal', [
            'meal_id'       => $meal->id,
            'ingredient_id' => $ingredientD->id,
            'form'          => '',
            'in_kit'        => true,
            'quantity'      => null,
        ]);

        $this->assertDatabaseHas('ingredient_meal', [
            'meal_id'       => $meal->id,
            'ingredient_id' => $ingredientE->id,
            'form'          => 'crushed',
            'in_kit'        => true,
            'quantity'      => null,
        ]);

        $this->assertDatabaseHas('ingredient_meal', [
            'meal_id'       => $meal->id,
            'ingredient_id' => $ingredientE->id,
            'form'          => 'powdered',
            'in_kit'        => true,
            'quantity'      => 'a bit',
        ]);


    }

    /**
     * @test
     */
    public function the_ingredients_are_required()
    {
        $meal = factory(Meal::class)->create();


        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/ingredients", [
            'ingredients' => null,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('ingredients');
    }

    /**
     * @test
     */
    public function the_ingredients_must_be_an_array()
    {
        $meal = factory(Meal::class)->create();


        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/ingredients", [
            'ingredients' => 'not-an-array',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('ingredients');
    }

    /**
     * @test
     */
    public function each_ingredient_must_be_an_array()
    {
        $meal = factory(Meal::class)->create();


        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/ingredients", [
            'ingredients' => ['not-an-array'],
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('ingredients.0');
    }

    /**
     * @test
     */
    public function the_id_for_each_ingredient_is_required()
    {
        $meal = factory(Meal::class)->create();


        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/ingredients", [
            'ingredients' => [
                ['id' => null, 'quantity' => 'test', 'in_kit' => false]
            ],
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('ingredients.0.id');
    }

    /**
     * @test
     */
    public function the_ingredient_id_must_exist_in_db()
    {
        $meal = factory(Meal::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/ingredients", [
            'ingredients' => [
                ['id' => 99, 'quantity' => 'test', 'in_kit' => false]
            ],
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('ingredients.0.id');
    }

    /**
     * @test
     */
    public function the_ingredient_in_kit_must_be_a_boolean()
    {
        $meal = factory(Meal::class)->create();
        $ingredient = factory(Ingredient::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/ingredients", [
            'ingredients' => [
                ['id' => $ingredient->id, 'quantity' => 'test', 'in_kit' => 'not-a-bool']
            ],
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('ingredients.0.in_kit');
    }


}
