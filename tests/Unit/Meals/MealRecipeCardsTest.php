<?php


namespace Tests\Unit\Meals;


use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MealRecipeCardsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function generate_recipe_card_for_a_meal()
    {
        Storage::fake('recipes');
        $this->fakeBrowsershotPdf();

        $meal = factory(Meal::class)->create();

        $path = $meal->createRecipeCard();

        Storage::disk('recipes')->assertExists($path);
    }
}
