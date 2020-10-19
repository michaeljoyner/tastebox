<?php

namespace Tests\Feature\Meals;

use App\Meals\Ingredient;
use App\Meals\Meal;
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

        $response = $this->asAdmin()->postJson("/admin/api/meals", []);

        $response->assertSuccessful();

        $this->assertCount(1, Meal::all());
        $meal = Meal::first();

        $this->assertEquals($meal->uniqueId, $response->json('uniqueId'));

        $this->assertDatabaseHas('meals', [
            'unique_id' => $meal->unique_id,
        ]);


    }
}
