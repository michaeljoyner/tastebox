<?php


namespace App\Purchases;


use App\DatePresenter;
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

    public static function forAdmin(OrderedKit $kit): array
    {
        return [
            'id' => $kit->id,
            'customer_name' => $kit->order->customerFullname(),
            'address' => $kit->deliveryAddress()->asString(),
            'delivery_date' => DatePresenter::pretty($kit->delivery_date),
            'menu_week' => $kit->menu_week_number,
            'meals' => $kit->meal_summary,
        ];
    }
}
