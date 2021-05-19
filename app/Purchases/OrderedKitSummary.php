<?php


namespace App\Purchases;


use App\DatePresenter;
use Illuminate\Support\Carbon;

class OrderedKitSummary
{
    public string $delivery_date;
    public array $meals;
    public string $delivery_address;

    public function __construct(Carbon $date, array $meals, Address $address)
    {
        $this->delivery_date = DatePresenter::prettyWithDay($date);
        $this->meals = collect($meals)
            ->map(fn($meal) => [
                'meal'     => $meal['name'],
                'servings' => $meal['servings']
            ])->all();
        $this->delivery_address = sprintf(
            "%s, %s, %s",
            $address->line_one,
            $address->line_two,
            $address->city
        );
    }

    public function toArray(): array
    {
        return [
            'delivery_date'    => $this->delivery_date,
            'delivery_address' => $this->delivery_address,
            'meals'            => $this->meals,
        ];
    }
}
