<?php

namespace Tests\Feature\Membership;

use App\Meals\Meal;
use App\Orders\Menu;
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

    /**
     *@test
     */
    public function can_view_a_recipe_that_is_currently_available_as_free_recipe()
    {
        $this->withoutExceptionHandling();
        $member = factory(User::class)->state('member')->create();

        $menu = factory(Menu::class)->state('current')->create();
        $meal = factory(Meal::class)->create();

        $menu->addFreeRecipes(collect([$meal]));

        $response = $this->actingAs($member)->get("/me/recipes/{$meal->unique_id}");
        $response->assertSuccessful();

    }
}
