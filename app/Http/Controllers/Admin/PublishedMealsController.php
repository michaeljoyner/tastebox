<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Meals\Meal;
use Illuminate\Http\Request;

class PublishedMealsController extends Controller
{
    public function store()
    {
        sleep(3);
        Meal::findOrFail(request('meal_id'))->publish();
    }

    public function destroy(Meal $meal)
    {
        $meal->retract();
    }
}
