<?php

namespace App\Http\Controllers;

use App\Meals\Meal;
use App\Meals\MealsPresenter;
use Illuminate\Http\Request;

class ViewRecipeCardController extends Controller
{
    public function show(Meal $meal)
    {
        return view('recipes.new-design.card', ['meal' => MealsPresenter::forRecipeCard($meal)]);
    }
}
