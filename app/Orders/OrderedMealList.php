<?php


namespace App\Orders;


use App\Meals\Meal;

class OrderedMealList
{
    public $meals;

    public function __construct()
    {
        $this->meals = [];
    }

    public function addMeal(Meal $meal, int $servings)
    {
        if (array_key_exists($meal->id, $this->meals)) {
            $this->meals[$meal->id]['servings'][$servings] = ($this->meals[$meal->id]['servings'][$servings] ?? 0) + 1;

            return;
        }

        $this->meals[$meal->id] = [
            'id'       => $meal->id,
            'name'     => $meal->name,
            'servings' => [$servings => 1],
        ];
    }

    public function toArray(): array
    {
        return collect($this->meals)
            ->map(fn($meal) => [
                'id'       => $meal['id'],
                'name'     => $meal['name'],
                'servings' => collect($meal['servings'])
                    ->map(fn($count, $size) => [
                        'size'  => $size,
                        'count' => $count,
                    ])->values()->all(),
                'total_servings' => collect($meal['servings'])
                    ->map(fn ($count, $size) => [$count, $size])
                    ->sum(fn ($pair) => $pair[0] * $pair[1])
            ])
            ->all();
    }


}
