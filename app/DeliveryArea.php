<?php

namespace App;

enum DeliveryArea: string
{
    case NOT_SET = 'not set';
    case HOWICK = "Howick";
    case HILTON = "Hilton";

    public function active(): bool
    {
        return match($this) {
            DeliveryArea::HILTON => true,
            DeliveryArea::HOWICK => true,
            DeliveryArea::NOT_SET => false,
        };
    }

    public static function activeAreas(): array
    {
        return collect(self::cases())
            ->filter(fn (DeliveryArea $area) => $area->active())
            ->mapWithKeys(fn (DeliveryArea $area) => [$area->value => $area->name])->all();
    }
}
