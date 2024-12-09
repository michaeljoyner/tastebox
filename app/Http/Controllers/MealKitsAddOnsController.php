<?php

namespace App\Http\Controllers;

use App\AddOns\AddOn;
use App\Purchases\ShoppingBasket;
use App\Rules\OnTheMenu;
use Illuminate\Http\Request;

class MealKitsAddOnsController extends Controller
{


    public function store($kit_id)
    {
        $basket = ShoppingBasket::for(request()->user());
        $menu = $basket->getMenuForKit($kit_id);

        request()->validate([
            'add_on_id' => ['required', 'exists:add_ons,id', new OnTheMenu($menu, 'addon')],
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        $addOn = AddOn::findOrFail(request('add_on_id'));

        $basket->addAddOnToKit($kit_id, $addOn, request('qty'));

        return $basket->getKit($kit_id)->toArray();
    }

    public function delete($kit_id, $add_on_uuid)
    {
        $basket = ShoppingBasket::for(request()->user());

        $basket->removeAddOnFromKit($kit_id, $add_on_uuid);

        return $basket->getKit($kit_id)->toArray();
    }
}
