<?php


namespace App\Purchases;


use App\Orders\Menu;

class OrderedKitPresenter
{
    public static function summary(OrderedKit $kit): OrderedKitSummary
    {
        return new OrderedKitSummary($kit->menu->delivery_from, $kit->meal_summary, $kit->deliveryAddress());
    }

    public static function adminSummary(OrderedKit $kit): OrderedKitAdminSummary
    {
        return new OrderedKitAdminSummary(
            $kit->menu->delivery_from,
            $kit->meal_summary,
            $kit->deliveryAddress(),
            $kit->status
        );
    }
}
