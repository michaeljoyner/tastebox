<?php


namespace Tests\Feature\Meals;


use App\Meals\Classification;
use App\Meals\Ingredient;
use App\Meals\Meal;
use App\Meals\MealPriceTier;
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

        $classificationA = factory(Classification::class)->create();
        $classificationB = factory(Classification::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}", [
            'name' => 'test name',
            'description' => 'test description',
            'allergens' => 'test allergens',
            'prep_time' => 100,
            'cook_time' => 250,
            'classifications' => [$classificationA->id, $classificationB->id],
            'price_tier' => MealPriceTier::PREMIUM->value,
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('meals', [
            'unique_id' => $meal->unique_id,
            'description' => 'test description',
            'allergens' => 'test allergens',
            'prep_time' => 100,
            'cook_time' => 250,
            'price_tier' => MealPriceTier::PREMIUM->value,
        ]);

        $this->assertDatabaseHas('classification_meal', [
            'classification_id' => $classificationA->id,
            'meal_id' => $meal->id,
        ]);

        $this->assertDatabaseHas('classification_meal', [
            'classification_id' => $classificationB->id,
            'meal_id' => $meal->id,
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
    public function classifications_must_be_an_array()
    {
        $this->assertFieldIsInvalid(['classifications' => 'not-an-array']);
    }

    /**
     *@test
     */
    public function classifications_can_be_empty()
    {
        $this->assertFieldIsValid(['classifications' => []]);
    }

    /**
     *@test
     */
    public function each_classification_must_be_integer()
    {
        $this->assertFieldIsInvalid(['classifications' => ['one', 'two']], 'classifications.0');
    }

    /**
     *@test
     */
    public function each_classification_must_exist_in_classifications_table()
    {
        $this->assertFieldIsInvalid(['classifications' => [99]], 'classifications.0');
    }

    /**
     *@test
     */
    public function the_price_tier_is_required_as_valid_meal_price_tier_value()
    {
        $this->assertFieldIsInvalid(['price_tier' => null]);
        $this->assertFieldIsInvalid(['price_tier' => 'not-a-price-tier']);
    }

    private function assertFieldIsValid($field)
    {
        $meal = Meal::createNew();

        $classificationA = factory(Classification::class)->create();
        $classificationB = factory(Classification::class)->create();

        $valid = [
            'name' => 'test name',
            'description' => 'test description',
            'allergens' => 'test allergens',
            'prep_time' => 100,
            'cook_time' => 250,
            'classifications' => [$classificationA->id, $classificationB->id],
            'price_tier' => MealPriceTier::PREMIUM->value,
        ];

        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/meals/{$meal->id}", array_merge($valid, $field));

        $response->assertSuccessful();
    }



    private function assertFieldIsInvalid($field, $error_key = null)
    {
        $meal = Meal::createNew();

        $ingredientA = factory(Ingredient::class)->create();
        $ingredientB = factory(Ingredient::class)->create();
        $ingredientC = factory(Ingredient::class)->create();
        $ingredientD = factory(Ingredient::class)->create();
        $ingredientE = factory(Ingredient::class)->create();

        $classificationA = factory(Classification::class)->create();
        $classificationB = factory(Classification::class)->create();

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
            ],
            'classifications' => [$classificationA->id, $classificationB->id],
        ];

        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/meals/{$meal->id}", array_merge($valid, $field));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($error_key ?? array_key_first($field));
    }
}
