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

        $order = Order::makeNew(request()->all('first_name', 'last_name', 'email', 'phone'), $basket->price());

        $basket
            ->kits
            ->reject(fn (Kit $kit) => $kit->meals->count() === 0)
            ->each(fn (Kit $kit) => $order->addKit($kit, $request->addressForKit($kit->id)));

        return PayFast::checkoutForm($order);
    }
}
