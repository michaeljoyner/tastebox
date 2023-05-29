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
}
