<?php

namespace App\Purchases;

use App\DatePresenter;

class OrderPresenter
{

    public static function forMember(Order $order)
    {
        return  [
            'date' => DatePresenter::pretty($order->created_at),
            'amount' => round($order->price_in_cents / 100, 2),
            'is_paid' => $order->is_paid,
            'amount_paid' => $order->is_paid ? round($order->payment?->amount_gross / 100, 2) : 0,
            'kits' => $order->orderedKits->map(fn (OrderedKit $kit) => $kit->summarize()->toArray()),
        ];
    }
}
