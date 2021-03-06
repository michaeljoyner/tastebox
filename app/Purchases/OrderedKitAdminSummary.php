<?php


namespace App\Purchases;


use App\DatePresenter;
use Illuminate\Support\Carbon;

class OrderedKitAdminSummary
{
    public string $delivery_date;
    public array $meals;
    public string $delivery_address;
    public string $status;

    public function __construct(Carbon $date, array $meals, Address $address, string $status)
    {
        $this->delivery_date = DatePresenter::pretty($date);
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
        $this->status = $status;
    }

    public function toArray(): array
    {
        return [
            'delivery_date'    => $this->delivery_date,
            'delivery_address' => $this->delivery_address,
            'meals'            => $this->meals,
            'status'           => $this->status,
        ];
    }
}
