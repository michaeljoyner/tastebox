<?php


namespace App\Purchases;


use App\DatePresenter;
use App\Meals\MealsPresenter;
use App\Orders\Menu;

class OrderedKitPresenter
{
    public static function summary(OrderedKit $kit): OrderedKitSummary
    {
        return new OrderedKitSummary(
            $kit->menu->delivery_from,
            $kit->meal_summary,
            $kit->deliveryAddress(),
            $kit->addOns->all(),
        );
    }

    public static function adminSummary(OrderedKit $kit): OrderedKitAdminSummary
    {
        return new OrderedKitAdminSummary(
            $kit->id,
            $kit->menu->delivery_from,
            $kit->meal_summary,
            $kit->deliveryAddress(),
            $kit->status,
            $kit->addOns->all(),
        );
    }

    public static function forAdmin(OrderedKit $kit): array
    {
        return [
            'id'            => $kit->id,
            'customer_name' => $kit->order->customerFullname(),
            'address'       => $kit->deliveryAddress()->toString(),
            'delivery_date' => DatePresenter::pretty($kit->delivery_date),
            'menu_week'     => $kit->menu_week_number,
            'meals'         => $kit->meal_summary,
            'add_ons'       => $kit->add_on_summary ?? [],
            'status'        => $kit->status,
        ];
    }

    public static function forMember(OrderedKit $kit)
    {
        return [
            'address'       => $kit->deliveryAddress()->toString(),
            'delivery_date' => DatePresenter::pretty($kit->delivery_date),
            'menu_week'     => $kit->menu_week_number,
            'meals'         => $kit->meals->map(fn($m) => MealsPresenter::forPublic($m)),
            'meal_summary'  => $kit->meal_summary,
        ];
    }
}
