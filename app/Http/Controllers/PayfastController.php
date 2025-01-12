<?php

namespace App\Http\Controllers;

use App\Events\OrderConfirmed;
use App\Purchases\Order;
use App\Purchases\ShoppingBasket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayfastController extends Controller
{
    public function success(Order $order)
    {
        $basket = ShoppingBasket::for(request()->user());
        $basket->clear();

        $fakesPayments = config('payfast.fake_payments');

        if(!$order->isPaid() || $fakesPayments) {
            $order->update(['status' => Order::STATUS_PENDING]);
        }

        if($fakesPayments) {
            event(new OrderConfirmed($order));
        }

        return redirect("/thank-you/{$order->order_key}");
    }

    public function cancelled(Order $order)
    {
        $order->fullDelete();

        return redirect('/checkout');
    }


}
