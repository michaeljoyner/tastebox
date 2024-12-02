<?php

namespace App\AddOns;

class AddOnPresenter
{

    public static function forPublic(AddOn $addOn): array
    {
        return [
            'uuid' => $addOn->uuid,
            'name' => $addOn->name,
            'description' => $addOn->description,
            'image' => $addOn->getFirstMediaUrl(AddOn::IMAGE, "web"),
            'thumb' => $addOn->getFirstMediaUrl(AddOn::IMAGE, "thumb"),
            'price_in_cents' => $addOn->price,
            'price_formatted' => "R" . number_format($addOn->price / 100, 2),
        ];
    }
}
