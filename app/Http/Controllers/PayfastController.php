<?php

namespace App\Http\Controllers;

use App\Purchases\Order;
use App\Purchases\ShoppingBasket;
use Illuminate\Http\Request;

class PayfastController extends Controller
{
    public function success(Order $order)
    {
        $basket = ShoppingBasket::for(request()->user());
        $basket->clear();

        return redirect("/thank-you/{$order->order_key}");
    }

    public function cancelled(Order $order)
    {
        $order->fullDelete();

        return redirect('/checkout');
    }


}
