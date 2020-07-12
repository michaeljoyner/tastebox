<?php


namespace App\Purchases;


class TestITNVaildator implements ITNValidator
{

    public $should_pass;

    public function __construct($should_pass)
    {
        $this->should_pass = $should_pass;
    }

    public function isValid(array $data = []): bool
    {
        return $this->should_pass;
    }
}
