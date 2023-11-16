<?php

namespace App\Http\Controllers;

use App\Http\Requests\CostingRequest;
use App\Meals\Costing;
use App\Meals\Meal;
use Illuminate\Http\Request;

class MealCostingsController extends Controller
{
    public function store(CostingRequest $request, Meal $meal)
    {
        $meal->addCosting($request->costingInfo());
    }

    public function update(CostingRequest $request, Costing $costing)
    {
        $costing->update($request->costingInfo());
    }

    public function delete(Costing $costing)
    {
        $costing->delete();
    }
}
