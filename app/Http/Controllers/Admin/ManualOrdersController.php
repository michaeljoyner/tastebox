<?php

namespace App\Http\Controllers\Admin;

use App\AddOns\AddOn;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManualOrderRequest;
use App\Meals\Meal;
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
            ->map(fn ($m) => ['meal' => Meal::find($m['id']), 'servings' => $m['servings']])
            ->reject(fn ($m) => !$m['meal'])
            ->each(fn ($m) => $kit->setMeal($m['meal'], $m['servings']));

        request()->collect('add_ons')
            ->map(function ($ad) use ($kit) {
                $addOn = AddOn::find($ad['id']);
                if(!$addOn) {
                    return;
                }
                $kit->setAddOn($addOn, $ad['qty']);
            });



        $customer = request()->all([
            'first_name',
            'last_name',
            'email',
            'phone',
        ]);

        $order = Order::manual($customer, $kit);
    }
}
