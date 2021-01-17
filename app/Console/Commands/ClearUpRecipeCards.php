<?php

namespace App\Console\Commands;

use App\Meals\RecipeCard;
use Illuminate\Console\Command;

class ClearUpRecipeCards extends Command
{

    protected $signature = 'menus:clear-recipes';


    protected $description = 'Command description';


    public function handle()
    {
        RecipeCard::clearDisk();
        return 0;
    }
}
