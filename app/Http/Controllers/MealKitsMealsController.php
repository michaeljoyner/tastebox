<?php

namespace App\Http\Controllers;

use App\Purchases\ShoppingBasket;
use App\Rules\OnTheMenu;
use Illuminate\Http\Request;

class MealKitsMealsController extends Controller
{
    public function store($kit_id)
    {
        $basket = ShoppingBasket::for(request()->user());
        $menu = $basket->getMenuForKit($kit_id);

        request()->validate([
            'meal_id' => ['required', 'exists:meals,id', new OnTheMenu($menu)],
            'servings' => ['required', 'integer', 'min:1'],
        ]);


        $basket->addMealToKit($kit_id, request('meal_id'), request('servings'));
    }

    public function destroy($kit_id, $menu_id)
    {
        $basket = ShoppingBasket::for(request()->user());
        $basket->removeMealFromKit($kit_id, intval($menu_id));
    }
}
