<?php

namespace App\View\Components;

use App\Purchases\Order;
use Illuminate\View\Component;

class OrderSummary extends Component
{
    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }


    public function render()
    {
        return view('components.order-summary');
    }

    public function customer()
    {
        return $this->order->customerFullname();
    }

    public function boxes()
    {
        return $this->order->orderedKits->values();
    }

    public function hasPhone(): bool
    {
        return !!  $this->order->phone;
    }

    public function phoneNumber(): string
    {
        return $this->order->phone;
    }

    public function price()
    {
        return $this->order->price_in_cents / 100;
    }
}
