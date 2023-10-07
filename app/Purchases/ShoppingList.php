<?php


namespace App\Purchases;



use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ShoppingList
{
    private Collection $items;

    public function __construct()
    {
        $this->items = collect([]);
    }

    public static function fromMealList(Collection $meals): static
    {
        return $meals->map(fn($meal) => [
            'id'             => $meal['meal']->id,
            'name'           => $meal['meal']->name,
            'ingredients'    => $meal['meal']->ingredients->toArray(),
            'total_servings' => $meal['servings']
        ])->reduce(function (ShoppingList $list, $meal) {
            foreach ($meal['ingredients'] as $ingredient) {
                if ($ingredient['in_kit']) {
                    $list->addItem(new ShoppingListItem([
                        'id'          => $ingredient['id'],
                        'description' => $ingredient['description'],
                        'quantity'    => $ingredient['quantity'],
                        'form'        => $ingredient['form'],
                        'meal'        => $meal['name'],
                        'servings'    => $meal['total_servings'],
                    ]));
                }
            }

            return $list;
        }, new ShoppingList());

    }

    public function addItem(ShoppingListItem $item)
    {
        if(!$this->hasItem($item)) {
            return $this->items->push($item);
        }
        return $this->mergeItem($item);
    }

    public function hasItem(ShoppingListItem$item): bool
    {
        return $this->items->contains(fn (ShoppingListItem $i) => $i->id === $item->id);
    }

    public function toArray(): array
    {
        return $this
            ->items
            ->sortBy(fn (ShoppingListItem $i) => Str::lower($i->item_name))
            ->map(fn (ShoppingListItem $item) => $item->toArray())->values()->all();
    }

    public function mergeItem(ShoppingListItem $item)
    {
        $existing = $this->items->first(fn (ShoppingListItem $i) => $i->id === $item->id);

        $existing->mergeWith($item);
    }
}
