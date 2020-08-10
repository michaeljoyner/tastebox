<?php


namespace App\Purchases;


use App\Meals\Meal;
use Illuminate\Support\Collection;

class KitMealSummary
{
    public Collection $meals;

    public function __construct(Collection $meals)
    {
        $this->meals = Meal::find($meals->pluck('id'))
                           ->map(fn($meal) => new KitMeal($meal,
                               $meals->first(fn($m) => $m['id'] === $meal->id)['servings']));

    }

    public function toArray(): array
    {
        return $this->meals->map(
            fn(KitMeal $meal) => [
                'id' => $meal->meal_id,
                'name' => $meal->name,
                'servings' => $meal->servings
            ]
        )->all();
    }
}
