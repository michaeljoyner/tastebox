<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MealFormRequest;
use App\Http\Resources\AdminMealResource;
use App\Meals\Meal;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MealsController extends Controller
{

    public function index()
    {
        $search = request('q', false);
        $classification_ids = collect(explode(",", request('classifications')))->filter(fn ($id) => !!intval($id));

        $meals = Meal::query()
            ->with('classifications', 'latestMenus')
            ->when($search, fn(Builder $query) => $query->where('name', 'LIKE', "%{$search}%"))
            ->when(
                $classification_ids->count(),
                function (Builder $query) use ($classification_ids) {
                    foreach ($classification_ids->all() as $id) {
                        $query->whereHas(
                            'classifications',
                            fn (Builder $q) => $q->where('classifications.id', $id)
                        );
                    }
                }
            )
            ->paginate(40);

        return AdminMealResource::collection($meals);
    }

    public function show(Meal $meal)
    {
        $meal->load([
            'media',
            'ingredients',
            'notes',
            'tallies',
            'classifications',
            'latestMenus',
            'costings',
        ]);

        return AdminMealResource::make($meal);
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


