<?php

namespace App\Console\Commands;

use App\Mail\SendRecipeCards;
use App\Meals\RecipeCard;
use App\Orders\Menu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendMenuRecipeCards extends Command
{
    protected $signature = 'menus:weekly-recipes';

    protected $description = 'Create and send the current menu\'s recipe cards';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $menu = Menu::nextToPrep();

        if (! $menu->id) {
            $this->warn('menu is not prepared');

            return 0;
        }

        $archive = RecipeCard::archiveForMenu($menu);

        Mail::to('stephjoyner18@gmail.com')
            ->queue(new SendRecipeCards(RecipeCard::disk()->path($archive)));

        Mail::to('alexandra.joyner@gmail.com')
            ->queue(new SendRecipeCards(RecipeCard::disk()->path($archive)));

        return 0;
    }
}
