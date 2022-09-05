<?php


namespace App\Purchases;


use App\DeliveryAddress;
use App\DeliveryArea;
use App\Events\AddressGivenForAllUnsetKits;
use App\Events\KitAddressUpdated;
use App\Meals\Meal;
use App\Orders\Menu;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Kit
{

    public string $id;
    public string $name;
    public ?string $deliver_with = null;

    public function __construct(public int $menu_id, public Collection $meals, public DeliveryAddress $delivery_address, int $place = 0 )
    {
        $this->id = Str::uuid()->toString();
        $this->name = $this->getName($place);
    }

    private function getName($index)
    {
        $names = [
            'Box One',
            'Box Two',
            'Box Three',
            'Box Four',
            'Box Five',
            'Box Six',
            'Box Seven',
            'Box Eight',
            ];

        return $names[$index];
    }

    public function isValid(): bool
    {
        $menu = Menu::find($this->menu_id);
        $cut_off = $menu->current_to->setHours(23)->setMinutes(59);
        return $cut_off->greaterThan(now()) && $menu->can_order;
    }

    public function eligibleForOrder(): bool
    {
        $menu_available = Menu::available()->where('id', $this->menu_id)->count();
        return $menu_available && ($this->meals->count() > 2);
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

    public function requiresAddress(): bool
    {
        return ($this->delivery_address->area === DeliveryArea::NOT_SET) || !$this->delivery_address->address;
    }

    public function setDeliveryAddress(DeliveryAddress $address, bool $as_secondary = false)
    {
        $this->delivery_address = $address;

        if(!$as_secondary) {
            $this->deliver_with = null;
        }


        KitAddressUpdated::dispatch($this->id, $address);


    }

    public function deliverWith(Kit $kit)
    {
        $this->delivery_address = $kit->delivery_address;

        if($kit->menu_id === $this->menu_id) {
            $this->deliver_with = $kit->id;
        }
    }

    public function price(): int
    {
        return $this->meals->sum('servings') * Meal::SERVING_PRICE;
    }

    public function mealSummary(): KitMealSummary
    {
        return new KitMealSummary($this->meals);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'id' => $this->id,
            'menu_id' => $this->menu_id,
            'meals' => $this->meals->values()->all(),
        ];
    }
}
