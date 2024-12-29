<?php


namespace App\Purchases;


use App\DatePresenter;
use App\DeliveryAddress;
use Illuminate\Support\Carbon;

class OrderedKitAdminSummary
{
    public int $kit_id;
    public string $delivery_date;
    public array $meals;
    public array $add_ons;
    public string $delivery_address;
    public string $status;

    public function __construct(int $kit_id, Carbon $date, array $meals, DeliveryAddress $address, string $status, array $add_ons = [])
    {
        $this->kit_id = $kit_id;
        $this->delivery_date = DatePresenter::pretty($date);
        $this->meals = collect($meals)
            ->map(fn($meal) => [
                'meal'     => $meal['name'],
                'servings' => $meal['servings']
            ])->all();
        $this->delivery_address = $address->toString();
        $this->status = $status;
        $this->add_ons = collect($add_ons)->map(fn($add_on) => [
            'qty' => $add_on->pivot->qty,
            'name' => $add_on->name,
            'price' => $add_on->price
        ])->values()->all();
    }

    public function toArray(): array
    {
        return [
            'id'               => $this->kit_id,
            'delivery_date'    => $this->delivery_date,
            'delivery_address' => $this->delivery_address,
            'meals'            => $this->meals,
            'status'           => $this->status,
            'add_ons'          => $this->add_ons,
        ];
    }
}
