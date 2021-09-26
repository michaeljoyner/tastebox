<?php

namespace Tests\Feature\Meals;

use App\Meals\Meal;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class CreateAMealNoteTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function create_a_note_for_a_meal()
    {
        $this->withoutExceptionHandling();

        $meal = factory(Meal::class)->create();

        $admin = factory(User::class)->state('admin')->create();

        $response = $this->actingAs($admin)->postJson("/admin/api/meals/{$meal->id}/notes", [
            'title' => 'test title',
            'body' => 'test body',
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('notes', [
            'notable_type' => Meal::class,
            'notable_id' => $meal->id,
            'title' => 'test title',
            'body' => 'test body',
            'author' => $admin->name,
        ]);
    }

    /**
     *@test
     */
    public function the_title_is_required()
    {
        $this->assertFieldIsInvalid(['title' => null]);
    }

    /**
     *@test
     */
    public function the_body_is_required()
    {
        $this->assertFieldIsInvalid(['body' => null]);
    }

    private function assertFieldIsInvalid(array $field)
    {
        $meal = factory(Meal::class)->create();

        $valid = [
            'title' => 'test title',
            'body' => 'test body',
        ];

        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/meals/{$meal->id}/notes", array_merge($valid, $field));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(array_key_first($field));
    }
}
