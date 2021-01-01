<?php

namespace App\Console\Commands;

use App\Orders\Menu;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class FixMenuCuttoffs extends Command
{

    protected $signature = 'menus:correct';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Correct the menu cut off dates to Thursday midnight';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Menu::all()->each(function( Menu $menu) {
           $menu->current_to = Carbon::parse($menu->current_to)->subDays(2)->endOfDay();
           $menu->save();
        });
        return 0;
    }
}
