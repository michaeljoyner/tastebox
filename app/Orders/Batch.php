<?php


namespace App\Orders;


use App\Meals\Meal;
use App\Purchases\OrderedKit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class Batch
{
    public Collection $kits;
    public int $week;
    public Carbon $delivery_date;
    public int $menu_id;

    public function __construct($kits, int $week, Carbon $delivery_date, int $menu_id)
    {
        $this->kits = $kits;
        $this->week = $week;
        $this->delivery_date = $delivery_date;
        $this->menu_id = $menu_id;
    }

    public function name(): string
    {
        return "Batch #{$this->week}";
    }

    public function menuId(): int
    {
        return $this->menu_id;
    }

    public function deliveryDate(): Carbon
    {
        return $this->delivery_date;
    }

    public function totalKits(): int
    {
        return $this->kits->count();
    }

    public function totalPackedMeals(): int
    {
        return $this->kits->sum(fn ($kit) => $kit->meals->count());
    }

    public function totalServings(): int
    {
        return $this->kits->sum(fn ($kit) => $kit->meals->sum(fn ($meal) => $meal->pivot->servings));
    }

    public function kitList(): array
    {
        $this->kits->load('order');

        return $this->kits->map(fn (OrderedKit $kit) => [
            'customer' => $kit->order->customer()->toArray(),
            'delivery_address' => $kit->deliveryAddress()->toArray(),
            'meals' => $kit
                ->meals
                ->map(fn ($meal) => [
                    'name' => $meal->name,
                    'servings' => $meal->pivot->servings
                ])->values()->all(),
        ])->values()->all();
    }

    public function mealList(): array
    {
        $list = $this->kits->reduce(function ($carry, $kit) {
            foreach ($kit->meals as $meal) {
                $carry->addMeal($meal, $meal->pivot->servings);
            }

            return $carry;
        }, new OrderedMealList());

        return array_values($list->toArray());
    }

    public function ingredientList(): array
    {
        return $this->mealsWithIngredients()
                    ->reduce(function ($list, $meal) {
                        foreach ($meal['ingredients'] as $ingredient) {
                            if ($ingredient['in_kit']) {
                                $list->addIngredient($ingredient, $meal['name'], $meal['total_servings']);
                            }
                        }
                        return $list;
                    }, new BatchIngredientsList)
            ->toArray();
    }

    private function mealsWithIngredients()
    {
        $mealList = $this->mealList();

        return Meal::with('ingredients')
                   ->find(collect($mealList)->pluck('id'))
                   ->map(fn($meal) => [
                       'id'             => $meal->id,
                       'name'           => $meal->name,
                       'ingredients'    => $meal->ingredients->toArray(),
                       'total_servings' => collect($mealList)
                           ->first(fn($m) => $m['id'] === $meal->id)['total_servings']
                   ]);
    }


}
