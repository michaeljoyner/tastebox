<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminMealResource;
use App\Meals\Meal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsedMealsController extends Controller
{
    public function index()
    {
        $meals = Meal::query()->with(
            'classifications', 'latestMenus', 'media'
        )->latest('updated_at')->get();

        return AdminMealResource::collection($meals);
    }
}
