<?php

namespace App\Console\Commands;

use App\Meals\Meal;
use App\Meals\MealsPresenter;
use Illuminate\Console\Command;
use Spatie\Browsershot\Browsershot;

class GenerateRecipeCard extends Command
{

    protected $signature = 'meal:recipe {slug}';


    protected $description = 'Command description';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $meal = Meal::where('unique_id', $this->argument('slug'))->first();

        if (!$meal) {
            $this->warn(sprintf('Meal with unique id of %s does not exist', $this->argument('slug')));

            return 0;
        }
        $html = view('recipes.card', ['meal' => MealsPresenter::forPublic($meal)])->render();

        Browsershot::html($html)->waitUntilNetworkIdle()
                   ->format('A4')
                   ->landscape()
                   ->margins(0, 0, 0, 0)
                   ->save('recipe_card.pdf');

        return 0;
    }
}
