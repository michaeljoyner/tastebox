<?php

namespace App\Orders;

use App\AddOns\AddOn;

class OrderedAddOnList
{

    public function __construct(public array $addOns = [])
    {
    }

    public function addAddOn(AddOn $addOn, int $qty)
    {
        if (array_key_exists($addOn->id, $this->addOns)) {
            $this->addOns[$addOn->id]['qty'] = $this->addOns[$addOn->id]['qty'] + $qty;

            return;
        }

        $this->addOns[$addOn->id] = [
            'id'   => $addOn->id,
            'name' => $addOn->name,
            'qty'  => $qty,
        ];
    }

    public function toArray(): array
    {
        return array_values($this->addOns);
    }
}
