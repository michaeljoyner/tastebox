<?php

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use App\Meals\Meal;
use Illuminate\Http\Request;

class RecipesController extends Controller
{
    public function index()
    {
        $recipes = request()
            ->user()
            ->upcomingKits()
            ->flatMap(fn ($kit) => $kit->meals)
            ->map(fn (Meal $meal) => $meal->asRecipe());
        return view('members.recipes.index', ['recipes' => $recipes]);
    }

    public function show(Meal $meal)
    {
        $ordered_meals = request()
            ->user()
            ->upcomingKits()
            ->flatMap(fn ($kit) => $kit->meals);

        if($ordered_meals->contains(fn ($m) => $meal->id === $m->id)) {
            return view('members.recipes.show', ['recipe' => $meal->asRecipe()]);
        }

        return redirect("/me/recipes")
            ->with("toast", ['type' => 'error', 'text' => 'You do not currently have access to that recipe']);

    }
}


