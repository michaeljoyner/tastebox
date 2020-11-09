<?php


namespace Tests\Feature\Meals;


use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateMealNutritionalInfoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function update_the_nutritional_info_for_a_meal()
    {
        $this->withoutExceptionHandling();

        $meal = factory(Meal::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/nutritional-info", [
            'serving_energy'  => '150',
            'serving_carbs'   => 50,
            'serving_fat'     => 70,
            'serving_protein' => 0,
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('meals', [
            'serving_energy'  => 150,
            'serving_carbs'   => 50,
            'serving_fat'     => 70,
            'serving_protein' => 0,
        ]);
    }

    /**
     * @test
     */
    public function empty_values_are_allowed()
    {
        $meal = factory(Meal::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/nutritional-info", [
            'serving_energy'  => '',
            'serving_carbs'   => null,
            'serving_fat'     => null,
            'serving_protein' => '',
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('meals', [
            'serving_energy'  => null,
            'serving_carbs'   => null,
            'serving_fat'     => null,
            'serving_protein' => null,
        ]);
    }

    /**
     *@test
     */
    public function the_energy_must_be_an_integer()
    {
        $this->assertFieldIsInvalid(['serving_energy' => 'not-an-integer']);
    }

    /**
     *@test
     */
    public function the_fat_must_be_an_integer()
    {
        $this->assertFieldIsInvalid(['serving_fat' => 'not-an-integer']);
    }

    /**
     *@test
     */
    public function the_carbs_must_be_an_integer()
    {
        $this->assertFieldIsInvalid(['serving_carbs' => 'not-an-integer']);
    }

    /**
     *@test
     */
    public function the_protein_must_be_an_integer()
    {
        $this->assertFieldIsInvalid(['serving_protein' => 'not-an-integer']);
    }

    private function assertFieldIsInvalid($field)
    {
        $meal = factory(Meal::class)->create();

        $valid = [
            'serving_energy'  => 100,
            'serving_carbs'   => 200,
            'serving_fat'     => 200,
            'serving_protein' => 50,
        ];

        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/meals/{$meal->id}/nutritional-info", array_merge($valid, $field));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(array_key_first($field));
    }
}
