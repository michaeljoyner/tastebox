<?php

namespace App\Console\Commands;

use App\Mail\SendRecipeCards;
use App\Meals\Meal;
use App\Orders\Menu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
        $menu = Menu::nextUp();

        if(!$menu->id) {
            $this->warn('menu is not prepared');
            return 0;
        }

        $zip = new \ZipArchive();

        $zip->open(storage_path("app/recipes/week_{$menu->weekOfYear()}.zip") , \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $menu->meals->map(fn (Meal $meal) => $meal->createRecipeCard())
        ->each(fn ($file) => $zip->addFile($file, Str::afterLast($file, "/")));
        $zip->close();

        Mail::to('stephjoyner18@gmail.com')->queue(new SendRecipeCards(storage_path("app/recipes/week_{$menu->weekOfYear()}.zip")));

        return 0;
    }
}
