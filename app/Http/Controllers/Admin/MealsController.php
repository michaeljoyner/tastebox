<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MealFormRequest;
use App\Meals\Meal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MealsController extends Controller
{

    public function index()
    {
        $sel = DB::raw('meal_menu.meal_id, max(meal_menu.menu_id), max(menus.current_from) as last_used');
        $lastUsed = DB::table('meal_menu')->select($sel)
                      ->leftJoin('menus', 'menus.id', '=', 'meal_menu.menu_id')
                      ->groupBy('meal_menu.meal_id');

        return Meal::with('ingredients', 'classifications', 'tallies', 'notes')
                   ->leftJoinSub(
                       $lastUsed,
                       'recent_inclusion',
                       fn($join) => $join->on('meals.id', '=', 'recent_inclusion.meal_id')
                   )
                   ->get()->map->asArrayForAdmin();
    }

    public function show(Meal $meal)
    {
        return $meal->asArrayForAdmin();
    }

    public function store(MealFormRequest $request)
    {
        $data = $request->formData();

        $meal =  Meal::createNew($data['meal_attributes'], $data['classifications']);
        $meal->logCreateActivity($request->user()->name);

        return $meal;
    }

    public function update(Meal $meal, MealFormRequest $request)
    {
        $meal->updateWithFormData($request->formData());
    }

    public function delete(Meal $meal)
    {
        $meal->safeDelete();
    }
}


