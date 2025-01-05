<?php

namespace App\Console\Commands;

use App\Orders\Menu;
use App\Purchases\OrderedKit;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class AdjustExistingOrdersAndMenusForNewDates extends Command
{

    protected $signature = 'app:adjust-dates';


    protected $description = 'Adjust dates on orders and menus for 2025 date changes';


    public function handle()
    {
        $menus = Menu::query()
            ->where('current_to', '>', now())
            ->get();

        $menus->each(fn(Menu $menu) => $menu->update([
            'current_to' => Carbon::parse($menu->current_to)->addDays(2)->setTimeFromTimeString('12:00:00'),
            'delivery_from' => Carbon::parse($menu->delivery_from)->subDay(),
        ]));

        $kits = $ordered_kits = OrderedKit
            ::query()
            ->where('delivery_date', '>', now())
            ->get();

        $kits->each(fn(OrderedKit $kit) => $kit->update(['delivery_date' => Carbon::parse($kit->delivery_date)->subDay()]));
    }
}
