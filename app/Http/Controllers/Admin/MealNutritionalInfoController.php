<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NutritionalInfoRequest;
use App\Meals\Meal;
use Illuminate\Http\Request;

class MealNutritionalInfoController extends Controller
{
    public function update(NutritionalInfoRequest $request, Meal $meal)
    {
        $meal->setNutritionalInfo($request->nutritionalInfo());
    }
}
