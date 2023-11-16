<?php

namespace Tests\Feature\Meals;

use App\DatePresenter;
use App\Meals\Meal;
use App\Meals\MealPriceTier;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AddMealCostingTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * @test
     */
    public function add_a_costing_to_a_meal()
    {
        $meal = factory(Meal::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/costings", [
            'cost'        => 'R87.65',
            'tier'        => MealPriceTier::PREMIUM->value,
            'date_costed' => now()->subDays(2)->format(DatePresenter::STANDARD),
            'note'        => 'test note'
        ]);
        $response->assertSuccessful();

        $this->assertCount(1, $meal->costings);
        $costing = $meal->costings->first();

        $this->assertSame("R87.65", $costing->cost);
        $this->assertSame(MealPriceTier::PREMIUM, $costing->tier);
        $this->assertTrue($costing->date_costed->isSameDay(now()->subDays(2)));
        $this->assertSame("test note", $costing->note);
    }

    /**
     * @test
     */
    public function the_cost_is_required()
    {
        $this->assertFieldIsInvalid(['cost' => null]);
    }

    /**
     * @test
     */
    public function the_date_costed_is_required_as_date()
    {
        $this->assertFieldIsInvalid(['date_costed' => null]);
        $this->assertFieldIsInvalid(['date_costed' => 'note-a-date']);
    }

    /**
     * @test
     */
    public function the_tier_is_required_as_a_valid_tier_value()
    {
        $this->assertFieldIsInvalid(['tier' => null]);
        $this->assertFieldIsInvalid(['tier' => 'not-a-tier-value']);
    }

    private function assertFieldIsInvalid(array $field)
    {
        $meal = factory(Meal::class)->create();

        $valid = [
            'cost'        => 'R87.65',
            'tier'        => MealPriceTier::PREMIUM->value,
            'date_costed' => now()->subDays(2)->format(DatePresenter::STANDARD),
            'note'        => 'test note'
        ];

        $response = $this
            ->asAdmin()
            ->postJson(
                "/admin/api/meals/{$meal->id}/costings",
                array_merge($valid, $field)
            );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(array_key_first($field));

    }
}
