<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Meals\Meal;
use Illuminate\Http\Request;

class MealRecipeNotesController extends Controller
{
    public function store(Meal $meal)
    {
        $meal->update(request()->only('public_recipe_notes'));
    }
}
