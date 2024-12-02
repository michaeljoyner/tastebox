<?php


namespace App\Orders;


use App\AddOns\AddOn;
use App\AddOns\AddOnPresenter;
use App\DatePresenter;
use App\Meals\MealsPresenter;

class MenuPresenter
{

    public static function forPublic(Menu $menu): array
    {
        return [
            'id'                     => $menu->id,
            'can_order'              => $menu->can_order,
            'orders_close_on'        => DatePresenter::standard($menu->ordersCloseDate()),
            'orders_close_on_pretty' => DatePresenter::pretty($menu->ordersCloseDate()),
            'current_from_date'      => $menu->current_from->format('Y-m-d'),
            'current_from_pretty'    => $menu->current_from->format('jS M, Y'),
            'current_to_date'        => $menu->current_to->format('Y-m-d'),
            'current_to_pretty'      => $menu->current_to->format('jS M, Y'),
            'current_range_pretty'   => DatePresenter::range($menu->current_from, $menu->current_to),
            'delivery_from_date'     => $menu->delivery_from->format('Y-m-d'),
            'delivery_from_pretty'   => DatePresenter::prettyWithDay($menu->delivery_from),
            'week_number'            => $menu->current_from->week,
            'is_current'             => $menu->isCurrent(),
            'status'                 => Menu::UPCOMING,
            'meals'                  => $menu->meals->map(fn($m) => MealsPresenter::forPublic($m))->values()->all(),
            'add_ons' => $menu->addOns->map(
                fn(AddOn $addOn) => AddOnPresenter::forPublic($addOn))->values()->all(),
        ];
    }
}
