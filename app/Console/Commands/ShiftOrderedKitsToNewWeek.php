<?php

namespace App\Console\Commands;

use App\Orders\Menu;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ShiftOrderedKitsToNewWeek extends Command
{

    protected $signature = 'orders:shift-menu {ids}';


    protected $description = 'Shift orders from given menus to next week';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $kits = collect(explode(',', $this->argument('ids')))
            ->map(fn ($id) => Menu::find($id))
            ->filter(fn ($m) => !!$m)
            ->flatMap(fn ($menu) => $menu->orderedKits)
            ->each(function ($kit) {
                $next_menu = Menu::find($kit->menu_id + 1);
                $kit->menu_id = $next_menu->id;
                $kit->delivery_date = $next_menu->delivery_from;
                $kit->menu_week_number = $next_menu->weekOfYear();
                $kit->save();
            });

        return 0;
    }
}
