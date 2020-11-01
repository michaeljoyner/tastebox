<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Meals\Meal;
use Illuminate\Http\Request;

class MealCopiesController extends Controller
{
    public function store(Meal $meal)
    {
        request()->validate([
            'name' => ['required'],
        ]);

        Meal::copy($meal, request('name'));
    }
}
