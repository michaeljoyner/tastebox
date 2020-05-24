<?php


namespace Tests\Feature\Meals;


use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublishMealTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function publish_a_meal()
    {
        $this->withoutExceptionHandling();

        $meal = factory(Meal::class)->state('private')->create();

        $response = $this->asAdmin()->postJson("/admin/api/published-meals", [
            'meal_id' => $meal->id,
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('meals', [
            'id' => $meal->id,
            'is_public' => true,
        ]);
    }
}
