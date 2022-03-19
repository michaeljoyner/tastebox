<?php

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use App\Meals\FreeRecipeMeal;
use App\Meals\Meal;
use App\Orders\Menu;
use Illuminate\Http\Request;

class RecipesController extends Controller
{
    public function index()
    {
        $customer_recipes = request()
            ->user()
            ->upcomingKits()
            ->flatMap(fn ($kit) => $kit->meals)
            ->map(fn (Meal $meal) => $meal->asRecipe());

        $free_recipes = Menu::nextUp()->freeRecipeMeals->map(fn (FreeRecipeMeal $meal) => $meal->meal->asRecipe());
        return view('members.recipes.index', ['recipes' => $customer_recipes->merge($free_recipes)]);
    }

    public function show(Meal $meal)
    {
        $ordered_meals = request()
            ->user()
            ->upcomingKits()
            ->flatMap(fn ($kit) => $kit->meals);

        $free_recipes = Menu::nextUp()->freeRecipeMeals->map(fn (FreeRecipeMeal $meal) => $meal->meal);

        if($ordered_meals->contains(fn ($m) => $meal->id === $m->id) || $free_recipes->contains($meal)) {
            return view('members.recipes.show', ['recipe' => $meal->asRecipe()]);
        }

        return redirect("/me/recipes")
            ->with("toast", ['type' => 'error', 'text' => 'You do not currently have access to that recipe']);

    }
}


