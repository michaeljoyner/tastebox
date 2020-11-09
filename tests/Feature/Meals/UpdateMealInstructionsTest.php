<?php


namespace Tests\Feature\Meals;


use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateMealInstructionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function update_the_instructions_for_a_meal()
    {
        $this->withoutExceptionHandling();

        $meal = factory(Meal::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/instructions", [
            'instructions' => 'test instructions',
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('meals', [
            'id'           => $meal->id,
            'instructions' => 'test instructions',
        ]);
    }
}
