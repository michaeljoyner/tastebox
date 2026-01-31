<?php

namespace App\Meals;

enum MealPriceTier: int
{
    case BUDGET = 1;
    case STANDARD = 2;
    case PREMIUM = 3;
    case DELUXE = 4;

    public function price(): int
    {
        return match ($this) {
            self::BUDGET => 85,
            self::STANDARD => 95,
            self::PREMIUM => 105,
            self::DELUXE => 115,
        };
    }

    public function priceAsString(): string
    {
        return 'R'.$this->price();
    }

    public function description(): string
    {
        return match ($this) {
            self::BUDGET => 'Basic',
            self::STANDARD => 'Standard',
            self::PREMIUM => 'Premium',
            self::DELUXE => 'Deluxe',
        };
    }
}
