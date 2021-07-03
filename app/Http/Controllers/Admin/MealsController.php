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

        return Meal::with('ingredients', 'classifications', 'tallies')
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

        return Meal::createNew($data['meal_attributes'], $data['classifications']);
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

//select * from `meals` inner join (select `meal_menu`.`meal_id, max(meal_menu`.`menu_id), max(menus`.`current_from)` as `last_used` from `meal_menus` left join `menus` on `menus`.`id` = `meal_menu`.`menu`.`id` group by `meal_menu`.`meal_id`) as `lastused` on `meals`.`id` = `lastused`.`meal_id`)"
//trace: [,…]
