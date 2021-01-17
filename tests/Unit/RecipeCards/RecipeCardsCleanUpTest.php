<?php


namespace Tests\Unit\RecipeCards;


use App\Meals\Meal;
use App\Meals\RecipeCard;
use App\Orders\Menu;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RecipeCardsCleanUpTest extends TestCase
{
    /**
     *@test
     */
    public function can_clear_up_the_recipes_disk()
    {
        Storage::fake(RecipeCard::DISK_NAME);

        $menu = factory(Menu::class)->create();
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();
        $menu->setMeals([$mealA->id, $mealB->id]);

        RecipeCard::forMeal($mealC);
        RecipeCard::archiveForMenu($menu);

        $this->assertCount(4, RecipeCard::disk()->allFiles());

        RecipeCard::clearDisk();

        $this->assertCount(0, RecipeCard::disk()->allFiles());
    }
}
