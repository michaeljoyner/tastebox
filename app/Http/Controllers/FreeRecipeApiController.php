<?php

namespace App\Http\Controllers;

use App\Http\Resources\FreeRecipeResource;
use App\Meals\FreeRecipeMeal;
use App\Meals\Meal;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;

class FreeRecipeApiController extends Controller
{
    public function index()
    {
        $meals = FreeRecipeMeal::query()
                               ->with(['meal' => ['ingredients', 'classifications']])
                               ->whereHas(
                                   'menu',
                                   fn(Builder $query) => $query->where('can_order', true)
                               )
                               ->get()
                               ->unique(fn(FreeRecipeMeal $freeRecipeMeal) => $freeRecipeMeal->meal_id)
                               ->map(fn(FreeRecipeMeal $freeRecipeMeal) => $freeRecipeMeal->meal);

        return ['data' => FreeRecipeResource::collection($meals)];
    }

    public function show(Meal $meal)
    {
        $meal->load(['ingredients', 'classifications']);

        return FreeRecipeResource::make($meal);
    }
}
