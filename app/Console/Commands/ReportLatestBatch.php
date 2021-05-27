<?php

namespace App\Console\Commands;

use App\Orders\BatchMealsTally;
use App\Orders\BatchReport;
use App\Orders\MealTally;
use App\Orders\Menu;
use Illuminate\Console\Command;

class ReportLatestBatch extends Command
{

    protected $signature = 'batch:report-latest';


    protected $description = 'Report the latest batch tallies';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {

        $menus = Menu::latest()
            ->doesntHave('batchReport')
                    ->where('current_to', '<=', now())->get();

        if(!$menus->count()) {
            return 0;
        }

        $menus->each(fn ($menu) => $menu);
        $menus->each(function(Menu $menu) {
            $menu->reportBatch();
            $batch = $menu->getBatch();
            $tallies = BatchMealsTally::fromBatch($batch);
            MealTally::forBatch($tallies);
        });

        return 0;
    }
}
