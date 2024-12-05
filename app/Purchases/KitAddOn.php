<?php

namespace App\Purchases;

use App\AddOns\AddOn;

class KitAddOn
{
    public int $add_on_id;
    public string $name;
    public int $qty;
    public int $price;

    public function __construct(AddOn $addOn, int $qty)
    {
        $this->add_on_id = $addOn->id;
        $this->name = $addOn->name;
        $this->price = $addOn->price;
        $this->qty = $qty;
    }
}
