<?php


namespace App\Purchases;


interface ITNValidator
{
    public function isValid(array $data = []): bool;
}
