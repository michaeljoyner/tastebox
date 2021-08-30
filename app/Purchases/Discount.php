<?php


namespace App\Purchases;


interface Discount
{
    const LUMP = 1;
    const PERCENTAGE = 2;

    public function getCode(): string;

    public function getValue(): int;

    public function getType(): int;

    public function isValid(): bool;

    public function use();

    public function discount(int $amount): int;
}
