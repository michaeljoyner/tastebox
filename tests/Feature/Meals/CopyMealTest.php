<?php


namespace Tests\Feature\Meals;


use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class CopyMealTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function copy_an_existing_meal()
    {
        $this->withoutExceptionHandling();

        $meal = factory(Meal::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/copies", [
            'name' => 'test name',
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('meals', [
            'name'        => 'test name',
            'description' => $meal->description,
        ]);
    }

    /**
     *@test
     */
    public function the_name_is_required()
    {
        $meal = factory(Meal::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/copies", [
            'name' => null,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('name');
    }
}
