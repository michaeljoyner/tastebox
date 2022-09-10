<?php

namespace App;

enum DeliveryArea: string
{
    case NOT_SET = 'not set';
    case HOWICK = "Howick";
    case HILTON = "Hilton";
    case PMB = 'pmb';
    case NOTTINGHAM_ROAD = 'notties';
    case NOTTINGHAM_ROAD_OUTER = 'notties_rural';
//    case KLOOF = 'kloof';
    case WARTBURG = 'wartburg';
    case CAMPERDOWN = 'camperdown';
    case CATO_RIDGE = 'cato_ridge';

    public function description(): string
    {
        return match ($this) {
            self::NOT_SET => 'No delivery area set',
            self::HOWICK => 'Howick',
            self::HILTON => 'Hilton',
            self::PMB => 'Pietermaritzburg',
            self::NOTTINGHAM_ROAD => 'Nottingham Road (in town)',
            self::NOTTINGHAM_ROAD_OUTER => 'Nottingham Road (out of town)',
            self::WARTBURG => 'Wartburg',
            self::CAMPERDOWN => 'Camperdown',
            self::CATO_RIDGE => 'Cato Ridge',
        };
    }

    public function active(): bool
    {
        return match ($this) {
            self::NOT_SET => false,
            self::HOWICK => true,
            self::HILTON => true,
            self::PMB => true,
            self::NOTTINGHAM_ROAD => true,
            self::NOTTINGHAM_ROAD_OUTER => true,
            self::WARTBURG => true,
            self::CAMPERDOWN => true,
            self::CATO_RIDGE => true,
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
