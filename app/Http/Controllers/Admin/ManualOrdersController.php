<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManualOrderRequest;
use App\Orders\Menu;
use App\Purchases\Address;
use App\Purchases\Order;
use App\Purchases\ShoppingBasket;
use Illuminate\Http\Request;

class ManualOrdersController extends Controller
{
    public function store(ManualOrderRequest $request)
    {
        $menu = Menu::nextUp();
        $basket = ShoppingBasket::for(null);
        $kit = $basket->addKit($menu->id);
        $kit->setDeliveryAddress($request->deliveryAddress());

        collect(request('meals'))
            ->each(fn ($m) => $kit->setMeal($m['id'], $m['servings']));



        $customer = request()->all([
            'first_name',
            'last_name',
            'email',
            'phone',
        ]);

        $order = Order::manual($customer, $kit);
    }
}
