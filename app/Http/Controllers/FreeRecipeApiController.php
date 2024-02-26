<?php

namespace App\Http\Controllers;

use App\Http\Resources\FreeRecipeResource;
use App\Meals\FreeRecipeMeal;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;

class FreeRecipeApiController extends Controller
{
    public function index()
    {
        $meals = FreeRecipeMeal::query()
                               ->with('meal')
                               ->whereHas(
                                   'menu',
                                   fn(Builder $query) => $query->where('can_order', true)
                               )
                               ->get()
                               ->unique(fn(FreeRecipeMeal $freeRecipeMeal) => $freeRecipeMeal->meal_id)
                               ->map(fn(FreeRecipeMeal $freeRecipeMeal) => $freeRecipeMeal->meal);

        return ['data' => FreeRecipeResource::collection($meals)];
    }
}
