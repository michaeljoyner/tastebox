<?php


namespace Tests\Feature\Meals;


use App\Meals\Ingredient;
use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteMealTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function delete_a_meal()
    {
        $this->withoutExceptionHandling();


        $meal = factory(Meal::class)->create();
        $ingredient = factory(Ingredient::class)->create();
        $meal->ingredients()->sync([$ingredient->id]);

        $response = $this->asAdmin()->deleteJson("/admin/api/meals/{$meal->id}");
        $response->assertSuccessful();

        $this->assertDatabaseMissing('meals', ['id' => $meal->id]);
        $this->assertDatabaseMissing('ingredient_meal', [
            'meal_id' => $meal->id,
            'ingredient_id' => $ingredient->id,
        ]);
    }
}
