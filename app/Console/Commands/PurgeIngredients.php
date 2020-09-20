<?php

namespace App\Console\Commands;

use App\Meals\Ingredient;
use Illuminate\Console\Command;

class PurgeIngredients extends Command
{

    protected $signature = 'meals:purge-ingredients';


    protected $description = 'Deletes all unused ingredients from the db';

    public function handle()
    {
        Ingredient::unused()->delete();
    }
}
