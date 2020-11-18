<?php

namespace App\Console\Commands;

use App\Meals\Classification;
use Illuminate\Console\Command;

class AddMealClassification extends Command
{

    protected $signature = 'meals:classifications {classification}';


    protected $description = 'Add a meal classification';


    public function handle()
    {
        $classification = $this->argument('classification');
        $exists = Classification::where('name', $classification)->count();

        if($exists) {
            $this->warn(sprintf("Sorry, %s has already been used", $classification));
            return 1;
        }

        Classification::create(['name' => $classification]);
        return 0;
    }
}
