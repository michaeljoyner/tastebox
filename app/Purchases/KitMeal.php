<?php


namespace App\Purchases;


use App\Meals\Meal;

class KitMeal
{
    public int $meal_id;
    public string $name;
    public int $servings;

    public function __construct(Meal $meal, $servings)
    {
        $this->meal_id = $meal->id;
        $this->name = $meal->name;
        $this->servings = $servings;
    }
}
