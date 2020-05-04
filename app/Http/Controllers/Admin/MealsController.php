<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MealFormRequest;
use App\Meals\Meal;
use Illuminate\Http\Request;

class MealsController extends Controller
{

    public function index()
    {
        return Meal::all()->map->asArrayForAdmin();
    }

    public function show(Meal $meal)
    {
        return $meal->asArrayForAdmin();
    }

    public function store()
    {
        return Meal::createNew();
    }

    public function update(Meal $meal, MealFormRequest $request)
    {
        $meal->updateWithFormData($request->formData());
    }
}
