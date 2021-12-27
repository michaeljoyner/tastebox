<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Meals\Meal;
use Illuminate\Http\Request;

class PublishedMealsController extends Controller
{
    public function store()
    {
        /* @var \App\Meals\Meal $meal */
        $meal = Meal::findOrFail(request('meal_id'));

        $meal->publish();

        $meal->logPublishActivity(request()->user()->name);
    }

    public function destroy(Meal $meal)
    {
        $meal->retract();
    }
}
