<?php


namespace App\Purchases;


use App\DatePresenter;
use App\DeliveryAddress;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class OrderedKitSummary
{
    public string $delivery_date;
    public array $meals;
    public string $delivery_address;
    public array $add_ons;

    public function __construct(Carbon $date, array $meals, DeliveryAddress $address, array $add_ons = [])
    {
        $this->delivery_date = DatePresenter::prettyWithDay($date);
        $this->meals = collect($meals)
            ->map(fn($meal) => [
                'meal'     => $meal['name'],
                'servings' => $meal['servings']
            ])->all();
        $this->delivery_address = $address->toString();
        $this->add_ons = collect($add_ons)->map(fn($add_on) => [
            'qty'   => $add_on->pivot->qty,
            'name'  => $add_on->name,
            'price' => $add_on->price
        ])->values()->all();
    }

    public function toArray(): array
    {
        return [
            'delivery_date'    => $this->delivery_date,
            'delivery_address' => $this->delivery_address,
            'meals'            => $this->meals,
            'add_ons'          => $this->add_ons,
        ];
    }
}
