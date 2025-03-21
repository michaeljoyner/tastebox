<?php


namespace App\Orders;


use App\DatePresenter;
use App\Meals\Meal;
use App\Purchases\OrderedKit;
use App\Purchases\ShoppingList;
use App\Purchases\ShoppingListItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;

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
        return $this->kits->sum(fn($kit) => $kit->meals->count());
    }

    public function totalServings(): int
    {
        return $this->kits->sum(fn($kit) => $kit->meals->sum(fn($meal) => $meal->pivot->servings));
    }

    public function kitList(): array
    {
        $this->kits->load('order');

        return $this->kits->map(fn(OrderedKit $kit) => [
            'customer'         => $kit->order->customer()->toArray(),
            'delivery_address' => $kit->deliveryAddress()->toArray(),
            'meals'            => $kit
                ->meals
                ->map(fn($meal) => [
                    'name'     => $meal->name,
                    'servings' => $meal->pivot->servings
                ])->values()->all(),
            'add_ons' => $kit->addOns->map(fn($addon) => [
                'name'     => $addon->name,
                'qty'      => $addon->pivot->qty,
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

    public function addOnList(): array
    {
        $list = $this->kits->reduce(function ($carry, OrderedKit $kit) {
            foreach ($kit->addOns as $addOn) {
               $carry->addAddOn($addOn, $addOn->pivot->qty);
            }
            return $carry;
        }, new OrderedAddOnList());

        return $list->toArray();
    }

    public function ingredientList(): array
    {
        return $this->mealsWithIngredients()
                    ->reduce(function ($list, $meal) {
                        foreach ($meal['meal']->ingredients->toArray() as $ingredient) {
                            if ($ingredient['in_kit']) {
                                $list->addIngredient(
                                    $ingredient,
                                    $meal['meal']->name,
                                    $meal['servings']
                                );
                            }
                        }

                        return $list;
                    }, new BatchIngredientsList)
                    ->toArray();
    }

    public function shoppingList(): array
    {
        return ShoppingList::fromMealList($this->mealsWithIngredients())->toArray();
    }

    public function createShoppingListPdf(): string
    {
        $file_name = sprintf("shopping_list_batch_%s.pdf", $this->week);

        return ShoppingList::fromMealList($this->mealsWithIngredients())
            ->saveAsPdf(
                file_name: $file_name,
                title: "Weekly Orders Shopping list: Week #{$this->week}",
                subtitle: "For batch to be delivered on" . DatePresenter::pretty($this->deliveryDate())
            );


    }

    private function mealsWithIngredients()
    {
        $mealList = $this->mealList();

        return Meal::with('ingredients')
                   ->find(collect($mealList)->pluck('id'))
                   ->map(fn($meal) => [
                       'meal'     => $meal,
                       'servings' => collect($mealList)
                           ->first(fn($m) => $m['id'] === $meal->id)['total_servings']
                   ]);
    }


}
