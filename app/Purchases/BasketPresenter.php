<?php


namespace App\Purchases;


use App\DatePresenter;
use App\DeliveryArea;
use App\Meals\Meal;
use App\Orders\Menu;

class BasketPresenter
{
    public function __construct(private ShoppingBasket $basket)
    {
    }

    public function presentKit(Kit $kit)
    {
        $meals = Meal::find($kit->meals->pluck('id'));
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
            'delivery_area' => $kit->delivery_address->area->name,
            'delivery_address' => $kit->delivery_address->address,
            'deliver_with' => $this->basket->getKitName($kit->deliver_with),
            'can_deliver' => !$kit->requiresAddress(),
            'meals' => $meals->map(fn (Meal $meal) => [
                'id' => $meal->id,
                'name' => $meal->name,
                'thumb' => $meal->titleImage('thumb'),
                'servings' => $kit->meals->first(fn ($m) => $m['id'] === $meal->id)['servings']
            ])->values()->all()
        ];
    }
}
