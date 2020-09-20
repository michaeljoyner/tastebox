<?php

namespace App\Purchases;

use App\Orders\Menu;
use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ShoppingBasket
{
    public $kits;

    public function __construct(Collection $kits)
    {
        $this->kits = $kits;
    }

    public static function for(?User $user): ShoppingBasket
    {
        return new ShoppingBasket(collect(session('basket.kits', [])));
    }

    public function getKit(string $kit_id): ?Kit
    {
        return $this->kits->first(fn (Kit $kit) => $kit->id === $kit_id);
    }

    public function addKit(int $menu_id)
    {
        $kit = new Kit($menu_id, null, $this->kits->count());

        $this->kits->push($kit);

        $this->save();

        return $kit;
    }

    public function addMealToKit(string $kit_id, int $meal_id, int $servings)
    {
        $kit = $this->getKit($kit_id);

        if(!$kit) {
            throw new \InvalidArgumentException("no kit with id {$kit_id} in basket");
        }

        $kit->setMeal($meal_id, $servings);

        $this->setKit($kit);

        $this->save();
    }

    public function removeMealFromKit(string $kit_id, int $meal_id)
    {
        $kit = $this->getKit($kit_id);

        if(!$kit) {
            throw new \InvalidArgumentException("no kit with id {$kit_id} in basket");
        }

        $kit->removeMeal($meal_id);

        $this->setKit($kit);

        $this->save();
    }


    private function setKit(Kit $kit)
    {
        $this->kits = $this->kits->map(
            fn(Kit $k) => $k->id === $kit->id ? $kit : $k
        );
    }

    public function discardKit(string $kit_id)
    {
        $this->kits = $this->kits->reject(
            fn (Kit $kit) => $kit->id === $kit_id
        );

        $this->save();
    }

    public function hasKit(string $kit_id): bool
    {
        return $this->kits->contains(fn (Kit $kit) => $kit->id === $kit_id);
    }



    public function getMenuForKit(string $kit_id): Menu
    {
        $kit = $this->kits->first(fn ($k) => $k->id === $kit_id);

        return Menu::find($kit->menu_id ?? null);
    }

    private function save()
    {
        session(['basket.kits' => $this->kits->all()]);
    }

    public function price(): float
    {
        return $this
            ->kits
            ->filter(fn(Kit $kit) => $kit->eligibleForOrder())
            ->sum(fn (Kit $kit) => $kit->price());
    }

    public function presentForReview()
    {
        $kits = $this
            ->kits
            ->filter->isValid()
            ->map(fn(Kit $kit) => (new BasketPresenter())->presentKit($kit))->values()->all();
        return [
            'total_boxes' => $this->kits->count(),
            'total_price' => $this->price(),
            'kits' => $kits,
        ];
    }

    public function clear()
    {
        $this->kits = collect([]);
        $this->save();
    }

    public function isEmpty()
    {
        return $this->kits->count() === 0;
    }


}
