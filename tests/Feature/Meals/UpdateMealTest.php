<?php


namespace Tests\Feature\Meals;


use App\Meals\Ingredient;
use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateMealTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function update_an_existing_meal()
    {
        $this->withoutExceptionHandling();
        $meal = Meal::createNew();

        $ingredientA = factory(Ingredient::class)->create();
        $ingredientB = factory(Ingredient::class)->create();
        $ingredientC = factory(Ingredient::class)->create();
        $ingredientD = factory(Ingredient::class)->create();
        $ingredientE = factory(Ingredient::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}", [
            'name' => 'test name',
            'description' => 'test description',
            'allergens' => 'test allergens',
            'prep_time' => 100,
            'cook_time' => 250,
            'instructions' => 'test instructions',
            'serving_energy' => '150',
            'serving_carbs' => 50,
            'serving_fat' => 70,
            'serving_protein' => 0,
            'ingredients' => [
                ['id' => $ingredientA->id, 'quantity' => '2', 'in_kit' => false],
                ['id' => $ingredientB->id, 'quantity' => '3 tsp', 'in_kit' => false],
                ['id' => $ingredientC->id, 'quantity' => '1 bag', 'in_kit' => true],
                ['id' => $ingredientD->id, 'quantity' => '', 'in_kit' => true],
                ['id' => $ingredientE->id, 'quantity' => null, 'in_kit' => true],
            ],
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('meals', [
            'unique_id' => $meal->unique_id,
            'description' => 'test description',
            'allergens' => 'test allergens',
            'prep_time' => 100,
            'cook_time' => 250,
            'instructions' => 'test instructions',
            'serving_energy' => '150',
            'serving_carbs' => 50,
            'serving_fat' => 70,
            'serving_protein' => 0,
        ]);

        $customerIngredients = $meal->customerIngredients()->get();
        $kitIngredients = $meal->kitIngredients()->get();

        $this->assertCount(2, $customerIngredients);
        $this->assertCount(3, $kitIngredients);

        $this->assertTrue($customerIngredients->contains($ingredientA));
        $this->assertTrue($customerIngredients->contains($ingredientB));
        $this->assertTrue($kitIngredients->contains($ingredientC));
        $this->assertTrue($kitIngredients->contains($ingredientD));
        $this->assertTrue($kitIngredients->contains($ingredientE));

        $this->assertDatabaseHas('ingredient_meal', [
            'meal_id' => $meal->id,
            'ingredient_id' => $ingredientA->id,
            'in_kit' => false,
            'quantity' => '2',
        ]);

        $this->assertDatabaseHas('ingredient_meal', [
            'meal_id' => $meal->id,
            'ingredient_id' => $ingredientB->id,
            'in_kit' => false,
            'quantity' => '3 tsp',
        ]);

        $this->assertDatabaseHas('ingredient_meal', [
            'meal_id' => $meal->id,
            'ingredient_id' => $ingredientC->id,
            'in_kit' => true,
            'quantity' => '1 bag',
        ]);

        $this->assertDatabaseHas('ingredient_meal', [
            'meal_id' => $meal->id,
            'ingredient_id' => $ingredientD->id,
            'in_kit' => true,
            'quantity' => null,
        ]);

        $this->assertDatabaseHas('ingredient_meal', [
            'meal_id' => $meal->id,
            'ingredient_id' => $ingredientE->id,
            'in_kit' => true,
            'quantity' => null,
        ]);
    }

    /**
     *@test
     */
    public function the_prep_time_must_be_an_integer()
    {
        $this->assertFieldIsInvalid(['prep_time' => 'not-an-integer']);
    }

    /**
     *@test
     */
    public function cook_time_must_be_an_integer()
    {
        $this->assertFieldIsInvalid(['cook_time' => 'not-an-integer']);
    }

    /**
     *@test
     */
    public function serving_energy_must_be_an_integer()
    {
        $this->assertFieldIsInvalid(['serving_energy' => 'not-an-integer']);
    }

    /**
     *@test
     */
    public function serving_carbs_must_be_an_integer()
    {
        $this->assertFieldIsInvalid(['serving_carbs' => 'not-an-integer']);
    }

    /**
     *@test
     */
    public function serving_protein_must_be_an_integer()
    {
        $this->assertFieldIsInvalid(['serving_protein' => 'not-an-integer']);
    }

    /**
     *@test
     */
    public function serving_fat_must_be_an_integer()
    {
        $this->assertFieldIsInvalid(['serving_fat' => 'not-an-integer']);
    }

    /**
     *@test
     */
    public function customer_ingredients_must_all_exist_in_ingredient_db()
    {
        $this->assertFieldIsInvalid(['customer_ingredients' => [99]], 'customer_ingredients.0');
    }

    /**
     *@test
     */
    public function kit_ingredients_must_all_exist_in_ingredient_db()
    {
        $this->assertFieldIsInvalid(['kit_ingredients' => [99]], 'kit_ingredients.0');
    }



    private function assertFieldIsInvalid($field, $error_key = null)
    {
        $meal = Meal::createNew();

        $ingredientA = factory(Ingredient::class)->create();
        $ingredientB = factory(Ingredient::class)->create();
        $ingredientC = factory(Ingredient::class)->create();
        $ingredientD = factory(Ingredient::class)->create();
        $ingredientE = factory(Ingredient::class)->create();

        $valid = [
            'name' => 'test name',
            'description' => 'test description',
            'allergens' => 'test allergens',
            'prep_time' => 100,
            'cook_time' => 250,
            'instructions' => 'test instructions',
            'serving_energy' => '150',
            'serving_carbs' => 50,
            'serving_fat' => 70,
            'serving_protein' => 0,
            'customer_ingredients' => [
                $ingredientA->id, $ingredientB->id,
            ],
            'kit_ingredients' => [
                $ingredientC->id,
                $ingredientD->id,
                $ingredientE->id,
            ]
        ];

        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/meals/{$meal->id}", array_merge($valid, $field));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($error_key ?? array_key_first($field));
    }
}
