<?php

namespace App\Http\Controllers\Admin;

use App\DatePresenter;
use App\Http\Controllers\Controller;
use App\Orders\Menu;
use Illuminate\Http\Request;

class CurrentBatchController extends Controller
{
    public function show()
    {
        $batch = Menu::nextUp()->getBatch();

        return [
            'name'           => $batch->name(),
            'total_kits'     => $batch->totalKits(),
            'total_meals'    => $batch->totalPackedMeals(),
            'total_servings' => $batch->totalServings(),
            'delivery_date'  => DatePresenter::pretty($batch->deliveryDate()),
            'kits'           => $batch->kitList(),
            'meals'          => $batch->mealList(),
            'ingredients'    => $batch->ingredientList(),
            'menu_id'        => $batch->menuId(),
        ];
    }
}
