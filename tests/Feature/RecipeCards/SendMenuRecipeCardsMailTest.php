<?php

namespace Tests\Feature\RecipeCards;

use App\Mail\SendRecipeCards;
use App\Meals\Meal;
use App\Meals\RecipeCard;
use App\Orders\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SendMenuRecipeCardsMailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function send_zip_archive_of_weeks_menu_as_email()
    {
        Mail::fake();
        Storage::fake(RecipeCard::DISK_NAME);

        $menu = factory(Menu::class)->state('current')->create();
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $menu->setMeals([$mealA->id, $mealB->id]);

        Artisan::call('menus:weekly-recipes');

        $zip_name = sprintf("menu_%s_recipes.zip", $menu->weekOfYear());
        Storage::disk(RecipeCard::DISK_NAME)->assertExists($zip_name);

        $expected_zip_location = Storage::disk(RecipeCard::DISK_NAME)->path($zip_name);

        Mail::assertQueued(
            SendRecipeCards::class,
            function (SendRecipeCards $mail) use ($expected_zip_location) {
                $this->assertTrue($mail->hasTo('stephjoyner18@gmail.com'));
                $this->assertSame($expected_zip_location, $mail->zip_location);

                return true;
            });


    }
}
