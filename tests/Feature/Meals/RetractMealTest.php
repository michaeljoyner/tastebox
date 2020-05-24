<?php


namespace Tests\Feature\Meals;


use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RetractMealTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function retract_a_public_meal()
    {
        $this->withoutExceptionHandling();

        $meal = factory(Meal::class)->state('public')->create();

        $response = $this->asAdmin()->deleteJson("/admin/api/published-meals/{$meal->id}");
        $response->assertSuccessful();

        $this->assertDatabaseHas('meals', [
            'id' => $meal->id,
            'is_public' => false,
        ]);
    }
}
