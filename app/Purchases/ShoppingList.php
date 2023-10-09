<?php


namespace App\Purchases;


use App\DatePresenter;
use App\Meals\MealShoppingList;
use App\MealShoppingListEntry;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;

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

    public static function fromMealShoppingList(MealShoppingList $list): static
    {
        $list->load('entries.meal');

        return static::fromMealList($list->entries->map(fn(MealShoppingListEntry $entry) => [
            'meal'     => $entry->meal,
            'servings' => $entry->servings,
        ]));
    }

    public function addItem(ShoppingListItem $item)
    {
        if (!$this->hasItem($item)) {
            return $this->items->push($item);
        }

        return $this->mergeItem($item);
    }

    public function hasItem(ShoppingListItem $item): bool
    {
        return $this->items->contains(fn(ShoppingListItem $i) => $i->id === $item->id);
    }

    public function toArray(): array
    {
        return $this
            ->items
            ->sortBy(fn(ShoppingListItem $i) => Str::lower($i->item_name))
            ->map(fn(ShoppingListItem $item) => $item->toArray())->values()->all();
    }

    public function mergeItem(ShoppingListItem $item)
    {
        $existing = $this->items->first(fn(ShoppingListItem $i) => $i->id === $item->id);

        $existing->mergeWith($item);
    }

    public function saveAsPdf(string $file_name, string $title, string $subtitle): string
    {
        $html = view('admin.batches.shopping-list', [
            'title'        => $title,
            'subtitle'     => $subtitle,
            'shoppingList' => $this->toArray(),
        ])->render();


        app(Browsershot::class)->setHtml($html)
                               ->format('A4')
                               ->margins(5, 5, 5, 25)
                               ->setNodeBinary(config('browsershot.node_path'))
                               ->setNpmBinary(config('browsershot.npm_path'))
                               ->savePdf(Storage::disk('admin_stuff')->path("shopping-lists/{$file_name}"));

        return "shopping-lists/{$file_name}";
    }
}
