<?php

namespace Tests\Feature\Meals;

use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddPublicRecipeNotesForMealTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function add_public_recipe_notes_to_a_meal()
    {
        $this->withoutExceptionHandling();

        $meal = factory(Meal::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/meals/{$meal->id}/public-recipe-notes", [
            'public_recipe_notes' => 'test notes',
        ]);
        $response->assertSuccessful();

        $this->assertSame('test notes', $meal->fresh()->public_recipe_notes);
    }
}
