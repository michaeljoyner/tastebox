<?php

namespace App\Console\Commands;

use App\Orders\Menu;
use Illuminate\Console\Command;

class GenerateMenus extends Command
{

    protected $signature = 'menus:next';


    protected $description = 'Generate the next menu';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        Menu::generateWeekly(1);
        return 0;
    }
}
