<?php

namespace App;

enum DeliveryArea: string
{
    case NOT_SET = 'not set';
    case HOWICK = "Howick";
    case HILTON = "Hilton";
    case PMB = 'pmb';
    case PMB_RURAL = 'pmb_rural';
    case NOTTINGHAM_ROAD = 'notties';
    case NOTTINGHAM_ROAD_OUTER = 'notties_rural';
    case KLOOF = 'kloof';
    case HILLCREST = 'hillcrest';
    case PINETOWN = 'pinetown';
    case WARTBURG = 'wartburg';
    case CAMPERDOWN = 'camperdown';
    case CATO_RIDGE = 'cato_ridge';
    case LIONS_RIVER = 'lions_river';
    case CURRYS_POST = 'currys_post';
    case ASHBURTON = 'ashburton';

    public function description(): string
    {
        return match ($this) {
            self::NOT_SET => 'No delivery area set',
            self::HOWICK => 'Howick',
            self::HILTON => 'Hilton',
            self::PMB => 'Pietermaritzburg (in the city)',
            self::PMB_RURAL => 'Pietermaritzburg (out of town)',
            self::NOTTINGHAM_ROAD => 'Nottingham Road (in town)',
            self::NOTTINGHAM_ROAD_OUTER => 'Nottingham Road (out of town)',
            self::WARTBURG => 'Wartburg',
            self::CAMPERDOWN => 'Camperdown',
            self::CATO_RIDGE => 'Cato Ridge',
            self::KLOOF => 'Kloof',
            self::CURRYS_POST => "Curry's Post",
            self::LIONS_RIVER => "Lion's River",
            self::PINETOWN => "Pinetown",
            self::HILLCREST => "Hillcrest",
            self::ASHBURTON => "Ashburton",
        };
    }

    public function active(): bool
    {
        return match ($this) {
            self::NOT_SET => false,
            self::HOWICK => true,
            self::HILTON => true,
            self::PMB => true,
            self::PMB_RURAL => true,
            self::NOTTINGHAM_ROAD => true,
            self::NOTTINGHAM_ROAD_OUTER => true,
            self::WARTBURG => true,
            self::CAMPERDOWN => true,
            self::CATO_RIDGE => true,
            self::KLOOF => true,
            self::CURRYS_POST => true,
            self::LIONS_RIVER => true,
            self::PINETOWN => true,
            self::HILLCREST => true,
            self::ASHBURTON => true,
        };
    }

    public function asKeyDescriptionPair(): array
    {
        return ['key' => $this->value, 'description' => $this->description()];
    }

    public static function activeAreas(): array
    {
        return collect(self::cases())
            ->filter(fn(DeliveryArea $area) => $area->active())
            ->sortBy(fn(DeliveryArea $area) => $area->description())
            ->map(fn(DeliveryArea $area) => $area->asKeyDescriptionPair())
            ->values()->all();
    }
}
