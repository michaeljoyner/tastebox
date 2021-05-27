<?php


namespace App\Orders;


use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class BatchMealsTally
{
    public function __construct(public Collection $meals, public Carbon $date)
    {
    }

    public static function fromBatch(Batch $batch): self
    {
        $meals = collect($batch->mealList())
            ->map(fn ($meal) => [
                'id' => $meal['id'],
                'total_ordered' => collect($meal['servings'])->sum(fn ($s) => $s['count']),
                'total_servings' => $meal['total_servings'],
            ]);

        $unused_meals = Menu::with('meals')->find($batch->menuId())
            ->meals
            ->reject(fn ($meal) => $meals->contains(fn ($m) => $m['id'] === $meal->id))
            ->each(fn ($meal) => $meals->push([
                'id' => $meal->id,
                'total_ordered' => 0,
                'total_servings' => 0,
            ]));

        return new self($meals, $batch->delivery_date);
    }

    public function forMeal(int $meal_id)
    {
        $meal = $this->meals->first(fn ($m) => $m['id'] === $meal_id);

        if(!$meal) {
            throw new \Exception('meal is not in batch tally');
        }

        return [
            'times_ordered' => $meal['total_ordered'],
            'servings_ordered' => $meal['total_servings'],
        ];
    }
}
