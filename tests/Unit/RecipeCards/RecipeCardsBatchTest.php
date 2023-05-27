<?php

namespace Tests\Unit\RecipeCards;

use App\Meals\Meal;
use App\Meals\RecipeCard;
use App\Orders\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RecipeCardsBatchTest extends TestCase {

    use RefreshDatabase;

    /**
     *@test
     */
    public function can_create_a_zip_archive_of_a_menus_recipe_cards()
    {
        Storage::fake(RecipeCard::DISK_NAME);
        $this->fakeBrowsershotPdf();

        $menu = factory(Menu::class)->create();
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();

        $menu->setMeals([$mealA->id, $mealB->id, $mealC->id]);

        $archive = RecipeCard::archiveForMenu($menu);

        Storage::disk(RecipeCard::DISK_NAME)->assertExists($archive);

        $zip = new \ZipArchive();
        $zip->open(Storage::disk(RecipeCard::DISK_NAME)->path($archive));

        $this->assertSame(3, $zip->count());
    }
}
