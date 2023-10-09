<?php

namespace App\Http\Controllers;

use App\Http\Resources\MealShoppingListResource;
use App\Meals\MealShoppingList;
use Illuminate\Http\Request;

class MealShoppingListsController extends Controller
{

    public function show(MealShoppingList $list)
    {
        $list->load('entries.meal');

        return MealShoppingListResource::make($list);
    }

    public function store()
    {

        request()->validate([
            'meals'           => ['required', 'array'],
            'meals.*'         => ['array'],
            'meals.*.meal_id' => ['exists:meals,id']
        ]);

        $list = MealShoppingList::fromMealList(request()->collect('meals'));

        $list->load('entries.meal');

        return MealShoppingListResource::make($list);
    }
}
