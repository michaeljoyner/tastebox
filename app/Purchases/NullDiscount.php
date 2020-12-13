<?php


namespace App\Purchases;


class NullDiscount implements Discount
{

    public function getCode(): string
    {
        return '';
    }

    public function getValue(): int
    {
        return 0;
    }

    public function getType(): int
    {
        return DiscountCode::LUMP;
    }

    public function isValid(): bool
    {
        return false;
    }

    public function use()
    {
        return null;
    }

    public function discount(int $amount): int
    {
        return $amount;
    }
}
