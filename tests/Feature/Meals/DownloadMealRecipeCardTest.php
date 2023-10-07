<?php


namespace Tests\Feature\Meals;


use App\Meals\Meal;
use App\Meals\RecipeCard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;
use Tests\TestCase;

class DownloadMealRecipeCardTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function download_a_recipe_card_for_an_existing_meal()
    {
        Storage::fake(RecipeCard::DISK_NAME);
        $this->withoutExceptionHandling();
        $this->fakeBrowsershotPdf();

        $meal = factory(Meal::class)->create();

        $response = $this->asAdmin()->post("/admin/api/meals/{$meal->id}/recipe-card");

        $response->assertSuccessful();

        $expected_header = sprintf("attachment; filename=%s.pdf", Str::slug($meal->name));

        $response->assertHeader('content-disposition', $expected_header);
    }


}
