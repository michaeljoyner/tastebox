<?php

namespace App\Http\Controllers;

use App\Purchases\Order;
use App\Purchases\ShoppingBasket;
use Illuminate\Http\Request;

class RevivedGuestOrdersController extends Controller
{
    public function store(Order $order)
    {
        $basket = ShoppingBasket::for(null);
        $basket->restoreFromOrder($order);

        return redirect("/basket");
    }
}
