<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Meals\Meal;
use Illuminate\Http\Request;

class MealInstructionsController extends Controller
{
    public function update(Meal $meal)
    {
        $meal->setInstructions(request('instructions'));
    }
}
