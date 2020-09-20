<?php


namespace Tests\Feature\Meals;


use App\Meals\Ingredient;
use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PurgeUnusedIngredientsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function purge_all_unused_ingredients_from_database()
    {
        $this->withoutExceptionHandling();

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();

        $ingredientA = factory(Ingredient::class)->create();
        $ingredientB = factory(Ingredient::class)->create();
        $ingredientC = factory(Ingredient::class)->create();
        $ingredientD = factory(Ingredient::class)->create();

        $mealA->ingredients()->sync([$ingredientA->id, $ingredientB->id]);
        $mealB->ingredients()->sync([$ingredientA->id, $ingredientD->id]);

        Artisan::call('meals:purge-ingredients');

        $this->assertDatabaseMissing('ingredients', ['id' => $ingredientC->id]);
        $this->assertDatabaseHas('ingredients', ['id' => $ingredientA->id]);
        $this->assertDatabaseHas('ingredients', ['id' => $ingredientB->id]);
        $this->assertDatabaseHas('ingredients', ['id' => $ingredientD->id]);
    }
}
