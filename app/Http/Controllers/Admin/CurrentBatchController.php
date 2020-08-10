<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Orders\Menu;
use Illuminate\Http\Request;

class CurrentBatchController extends Controller
{
    public function show()
    {
        $batch = Menu::nextUp()->getBatch();

        return [
            'name'        => $batch->name(),
            'kits'        => $batch->kitList(),
            'meals'       => $batch->mealList(),
            'ingredients' => $batch->ingredientList(),
        ];
    }
}
