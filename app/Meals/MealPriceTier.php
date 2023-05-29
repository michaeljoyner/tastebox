<?php

namespace App\Meals;

enum MealPriceTier: int
{
    case BUDGET = 1;
    case STANDARD = 2;
    case PREMIUM = 3;

    public function price(): int
    {
        return match ($this) {
            self::BUDGET => 85,
            self::STANDARD => 95,
            self::PREMIUM => 105,
        };
    }

    public function priceAsString(): string
    {
        return "R" . $this->price();
    }

    public function description(): string
    {
        return match ($this) {
            self::BUDGET => 'Basic',
            self::STANDARD => 'Standard',
            self::PREMIUM => 'Premium',
        };
    }
}
