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
