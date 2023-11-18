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

        $user_name = $request->user()->name;
        $logActivity = sprintf(
            "%s added a new costing for %s",
            $user_name,
            $meal->name
        );
        $meal->logActivity(
            actor: $user_name,
            activity: $logActivity,
            url: '/meals/' . $meal->id . '/manage/costings'
        );
    }

    public function update(CostingRequest $request, Costing $costing)
    {
        $info = $request->costingInfo();
        $costing->meal->update(['price_tier' => $info['tier']]);
        $costing->update($request->costingInfo());
    }

    public function delete(Costing $costing)
    {
        $costing->delete();
    }
}
