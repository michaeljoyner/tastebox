<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Meals\Meal;
use Illuminate\Http\Request;

class OrganisedMealIngredientsController extends Controller
{
    public function update(Meal $meal)
    {
        request()->validate([
            'ingredients'            => ['array'],
            'ingredients.*.id'       => ['exists:ingredient_meal,ingredient_id'],
            'ingredients.*.position' => ['integer']
        ]);

        $meal->organizeIngredients(request('ingredients'));
    }
}
