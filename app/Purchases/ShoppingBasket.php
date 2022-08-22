<?php

namespace App\Purchases;

use App\DeliveryAddress;
use App\Orders\Menu;
use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ShoppingBasket
{
    const SESSION = 'session';
    const DB = 'db';


    public function __construct(public Collection $kits, private string $storage_type, private ?User $owner = null)
    {}

    public static function for(?User $user): ShoppingBasket
    {
        if(!$user) {
            return new ShoppingBasket(collect(session('basket.kits', [])), self::SESSION);
        }

        if(!$user->shoppingBasket) {
            $user->shoppingBasket()->create(['contents' => serialize([])]);
        }
        $contents = unserialize($user->fresh()->shoppingBasket->contents);
        return new ShoppingBasket(collect($contents), self::DB, $user);

    }

    public function getKit(string $kit_id): ?Kit
    {
        return $this->kits->first(fn (Kit $kit) => $kit->id === $kit_id);
    }

    public function addKit(int $menu_id)
    {

        $kit = new Kit($menu_id, collect([]), DeliveryAddress::for($this->owner), $this->kits->count());

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
        if($this->storage_type === self::SESSION) {
            session(['basket.kits' => $this->kits->all()]);
        }

        if($this->storage_type === self::DB) {
            $this->owner->fresh()->shoppingBasket->update(['contents' => serialize($this->kits->all())]);
        }
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
