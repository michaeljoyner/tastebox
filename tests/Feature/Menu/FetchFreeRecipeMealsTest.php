<?php

namespace Feature\Menu;

use App\Meals\Meal;
use App\Orders\Menu;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class FetchFreeRecipeMealsTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * @test
     */
    public function fetch_all_free_recipe_meals_from_open_menus()
    {
        $menuA = factory(Menu::class)->create([
            'can_order'     => true,
            'current_from'  => Carbon::today()->addWeeks(1)->startOfWeek(),
            'current_to'    => Carbon::today()->addWeeks(1)->startOfWeek()->addDays(5),
            'delivery_from' => Carbon::today()->addWeeks(1)->endOfWeek()->addDay(),
        ]);
        $menuB = factory(Menu::class)->create([
            'can_order'     => true,
            'current_from'  => Carbon::today()->addWeeks(2)->startOfWeek(),
            'current_to'    => Carbon::today()->addWeeks(2)->startOfWeek()->addDays(5),
            'delivery_from' => Carbon::today()->addWeeks(2)->endOfWeek()->addDay(),
        ]);
        $menuC = factory(Menu::class)->create([
            'can_order'     => false,
            'current_from'  => Carbon::today()->addWeeks(3)->startOfWeek(),
            'current_to'    => Carbon::today()->addWeeks(3)->startOfWeek()->addDays(5),
            'delivery_from' => Carbon::today()->addWeeks(3)->endOfWeek()->addDay(),
        ]);

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $mealD = factory(Meal::class)->create();
        $mealE = factory(Meal::class)->create();

        $menuA->addFreeRecipeMeal($mealA);
        $menuA->addFreeRecipeMeal($mealB);
        $menuB->addFreeRecipeMeal($mealC);
        $menuB->addFreeRecipeMeal($mealD);
        $menuB->addFreeRecipeMeal($mealA);
        $menuC->addFreeRecipeMeal($mealE);

        $response = $this->asGuest()->getJson("/api/free-recipes");
        $response->assertSuccessful();

        $fetched = collect($response->json("data"));
        $this->assertCount(4, $fetched);


        $this->assertTrue(
            $fetched->contains(fn ($recipe) => $recipe['meal_id'] === $mealA->id),
        );
        $this->assertTrue(
            $fetched->contains(fn ($recipe) => $recipe['meal_id'] === $mealB->id),
        );
        $this->assertTrue(
            $fetched->contains(fn ($recipe) => $recipe['meal_id'] === $mealB->id),
        );
        $this->assertTrue(
            $fetched->contains(fn ($recipe) => $recipe['meal_id'] === $mealB->id),
        );

    }
}
