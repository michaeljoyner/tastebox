<?php

namespace Tests\Feature\Meals;

use App\Meals\Classification;
use App\Meals\Ingredient;
use App\Meals\Meal;
use App\Meals\MealPriceTier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateMealTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function create_a_new_meal()
    {
        $this->withoutExceptionHandling();

        $classificationA = factory(Classification::class)->create();
        $classificationB = factory(Classification::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/meals", [
            'name' => 'test name',
            'description' => 'test description',
            'allergens' => 'test allergens',
            'prep_time' => 100,
            'cook_time' => 250,
            'classifications' => [$classificationA->id, $classificationB->id],
            'price_tier' => MealPriceTier::BUDGET->value
        ]);
        $response->assertSuccessful();

        $this->assertCount(1, Meal::all());
        $meal = Meal::first();

        $this->assertSame($meal->id, $response->json('id'));

        $this->assertDatabaseHas('meals', [
            'unique_id' => $meal->unique_id,
            'description' => 'test description',
            'allergens' => 'test allergens',
            'prep_time' => 100,
            'cook_time' => 250,
            'price_tier' => MealPriceTier::BUDGET->value
        ]);



        $this->assertEquals($meal->uniqueId, $response->json('uniqueId'));

        $this->assertDatabaseHas('classification_meal', [
            'classification_id' => $classificationA->id,
            'meal_id' => $meal->id,
        ]);

        $this->assertDatabaseHas('classification_meal', [
            'classification_id' => $classificationB->id,
            'meal_id' => $meal->id,
        ]);


    }
}
