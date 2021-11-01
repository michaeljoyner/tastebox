<?php

namespace Tests\Feature\Membership;

use App\Meals\Meal;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberRecipeViewTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function a_member_cannot_view_recipes_that_are_not_in_their_upcoming_orders()
    {
        $this->withoutExceptionHandling();

        $member = factory(User::class)->state('member')->create();
        $meal = factory(Meal::class)->create();

        $response = $this->actingAs($member)->get("/me/recipes/{$meal->unique_id}");
        $response->assertRedirect("/me/recipes");

        $response->assertSessionHas("toast", [
            'type' => 'error',
            'text' => 'You do not currently have access to that recipe'
        ]);
    }
}
