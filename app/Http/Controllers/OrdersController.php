<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceOrderRequest;
use App\Purchases\Kit;
use App\Purchases\Order;
use App\Purchases\PayFast;
use App\Purchases\ShoppingBasket;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function store(PlaceOrderRequest $request)
    {
        $basket = ShoppingBasket::for(request()->user());
        $kits = $basket->kits->filter(fn (Kit $kit) => $kit->eligibleForOrder());

        $order = Order::makeNew($request->customerDetails(), $request->adressedKits($kits), $request->discount());

        return PayFast::checkoutForm($order);
    }
}
