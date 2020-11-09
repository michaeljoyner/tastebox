<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetIngredientsRequest;
use App\Meals\Meal;
use Illuminate\Http\Request;

class MealIngredientsController extends Controller
{
    public function update(SetIngredientsRequest $request, Meal $meal)
    {
        $meal->setIngredients($request->ingredientsList());
    }
}
