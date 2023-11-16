<?php

namespace Tests\Feature\Meals;

use App\DatePresenter;
use App\Meals\Costing;
use App\Meals\MealPriceTier;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateMealCostingTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * @test
     */
    public function update_an_existing_costing()
    {
        $costing = factory(Costing::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/costings/{$costing->id}", [
            'cost'        => 'R12.34',
            'tier'        => MealPriceTier::BUDGET->value,
            'date_costed' => now()->format(DatePresenter::STANDARD),
            'note'        => 'new test note'
        ]);
        $response->assertSuccessful();

        $costing->refresh();

        $this->assertSame("R12.34", $costing->cost);
        $this->assertSame(MealPriceTier::BUDGET, $costing->tier);
        $this->assertTrue($costing->date_costed->isSameDay(now()));
        $this->assertSame("new test note", $costing->note);
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

    private function assertFieldISInvalid(array $field)
    {
        $costing = factory(Costing::class)->create();

        $valid = [
            'cost'        => 'R12.34',
            'tier'        => MealPriceTier::BUDGET->value,
            'date_costed' => now()->format(DatePresenter::STANDARD),
            'note'        => 'new test note'
        ];

        $response = $this
            ->asAdmin()
            ->postJson(
                "/admin/api/costings/{$costing->id}",
                array_merge($valid, $field)
            );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(array_key_first($field));
    }
}
