<?php


namespace App\Purchases;


use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Kit
{

    public int $menu_id;
    public string $id;
    public Collection $meals;

    public function __construct(int $menu_id, ?Collection $meals = null)
    {
        $this->id = Str::uuid()->toString();
        $this->menu_id = $menu_id;
        $this->meals = $meals ?? collect([]);
    }

    public function setMeal(int $meal_id, int $servings)
    {
        if($this->meals->contains(fn($m) => $m['id'] === $meal_id)) {
            return $this->meals = $this->meals->map(
                fn ($m) => $m['id'] === $meal_id ? ['id' => $meal_id, 'servings' => $servings] : $m
            );
        }

        $this->meals->push(['id' => $meal_id, 'servings' => $servings]);
    }

    public function removeMeal(int $meal_id)
    {
        $this->meals = $this->meals->reject(
            fn ($m) => $m['id'] === $meal_id
        );
    }
}
