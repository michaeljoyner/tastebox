<?php


namespace App\Purchases;


use App\AddOns\AddOn;
use App\DatePresenter;
use App\DeliveryArea;
use App\Meals\Meal;
use App\Meals\MealPriceTier;
use App\Orders\Menu;

class BasketPresenter
{
    public function __construct(private ShoppingBasket $basket)
    {
    }

    public function presentKit(Kit $kit)
    {
        $meals = Meal::find($kit->meals->pluck('id'));
        $addOns = AddOn::find($kit->addOns->pluck('id'));
        $menu = Menu::find($kit->menu_id);

        return [
            'name' => $kit->name,
            'id' => $kit->id,
            'menu_id' => $kit->menu_id,
            'delivery_date' => DatePresenter::prettyWithDay($menu->delivery_from),
            'eligible_for_order' => $kit->eligibleForOrder(),
            'meals_count' => $meals->count(),
            'servings_count' => $kit->meals->sum('servings'),
            'price' => $kit->price(),
            'delivery_area' => $kit->delivery_address->area->description(),
            'delivery_address' => $kit->delivery_address->address,
            'deliver_with' => $this->basket->getKitName($kit->deliver_with),
            'can_deliver' => !$kit->requiresAddress(),
            'meals' => $meals->map(function (Meal $meal) use ($kit) {
                $servings = $kit->meals->first(fn ($m) => $m['id'] === $meal->id)['servings'];
                return [
                    'id' => $meal->id,
                    'name' => $meal->name,
                    'thumb' => $meal->titleImage('thumb'),
                    'servings' => $servings,
                    'price' => ($meal->price_tier?->price() ?? MealPriceTier::STANDARD->price()) * $servings,
                ];
            })->values()->all(),
            'add_ons' => $addOns->map(function (AddOn $addOn) use ($kit) {
                $qty = $kit->addOns->first(fn($a) => $a['id'] === $addOn->id)['qty'];
                return [
                    'id' => $addOn->id,
                    'name' => $addOn->name,
                    'thumb' => $addOn->getFirstMediaUrl(AddOn::IMAGE, 'thumb'),
                    'qty' => $qty,
                    'price' => ($addOn->price / 100) * $qty,
                ];
            })->values()->all(),
        ];
    }
}
