<?php

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use App\Purchases\Order;
use App\Purchases\ShoppingBasket;
use Illuminate\Http\Request;

class RevivedMemberOrdersController extends Controller
{
    public function store(Order $order)
    {
        $basket = ShoppingBasket::for(request()->user());
        $basket->restoreFromOrder($order);

        return redirect("basket");
    }
}
