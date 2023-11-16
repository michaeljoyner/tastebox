<?php

namespace Tests\Feature\Meals;

use App\Meals\Costing;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DeleteCostingTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     *@test
     */
    public function delete_an_existing_costing()
    {
        $costing = factory(Costing::class)->create();

        $response = $this->asAdmin()->deleteJson("/admin/api/costings/{$costing->id}");
        $response->assertSuccessful();

        $this->assertModelMissing($costing);
    }
}
