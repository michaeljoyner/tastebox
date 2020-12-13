<?php


namespace App\Purchases;


interface Discount
{
    public function getCode(): string;

    public function getValue(): int;

    public function getType(): int;

    public function isValid(): bool;

    public function use();

    public function discount(int $amount): int;
}
