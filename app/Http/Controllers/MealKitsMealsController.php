<?php

namespace App\Http\Controllers;

use App\Meals\Meal;
use App\Purchases\ShoppingBasket;
use App\Rules\OnTheMenu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MealKitsMealsController extends Controller
{
    public function store($kit_id)
    {
        $basket = ShoppingBasket::for(request()->user());
        $menu = $basket->getMenuForKit($kit_id);

        request()->validate([
            'meal_id' => ['required', 'exists:meals,id', new OnTheMenu($menu)],
            'servings' => ['required', 'integer', Rule::in([1,2,4])],
        ]);

        $basket->addMealToKit(
            $kit_id,
            Meal::find(request('meal_id')),
            request('servings')
        );

        return $basket->getKit($kit_id)->toArray();
    }

    public function destroy($kit_id, $menu_id)
    {
        $basket = ShoppingBasket::for(request()->user());
        $basket->removeMealFromKit($kit_id, intval($menu_id));

        return $basket->getKit($kit_id)->toArray();
    }
}
