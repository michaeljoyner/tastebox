<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Orders\Menu;
use Illuminate\Http\Request;

class MenuMealsController extends Controller
{
    public function store(Menu $menu)
    {
        request()->validate([
            'meal_ids' => ['present', 'array'],
            'meal_ids.*' => ['exists:meals,id']
        ]);

        $menu->setMeals(request('meal_ids'));
    }
}
