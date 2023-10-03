<?php

namespace App\Console\Commands;

use App\Meals\Meal;
use Illuminate\Console\Command;

class PopulateRecipeCardDescriptions extends Command
{

    protected $signature = 'meals:populate-recipe-card-descriptions';

    protected $description = 'Copies meal descriptions into recipe card descriptions if empty';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Meal::all()
            ->each(function(Meal $meal) {
                if(!$meal->meal_card_description) {
                    $meal->update(['meal_card_description' => $meal->description]);
                }
            });
    }
}
