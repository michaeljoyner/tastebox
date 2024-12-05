<?php

namespace App\Purchases;

use App\AddOns\AddOn;
use Illuminate\Support\Collection;

class KitAddOnSummary
{
    public Collection $addOns;

    public function __construct(Collection $addOns)
    {
        $this->addOns = AddOn
            ::find($addOns->pluck('id'))
            ->map(fn(AddOn $addOn) => new KitAddOn(
                $addOn,
                $addOns->first(fn($add_on) => $add_on['id'] === $addOn->id)['qty']
            ));
    }

    public function toArray()
    {
        return $this->addOns->map(fn(KitAddOn $addOn) => [
            'id'    => $addOn->add_on_id,
            'name'  => $addOn->name,
            'qty'   => $addOn->qty,
            'price' => $addOn->price,
        ])->values()->all();
    }
}
