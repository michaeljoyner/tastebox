<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Meals\Meal;
use App\Meals\RecipeCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MealRecipeCardController extends Controller
{
    public function show(Meal $meal)
    {
        $file = $meal->createRecipeCard();

        return Storage::disk(RecipeCard::DISK_NAME)->download($file);
    }
}
