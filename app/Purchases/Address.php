<?php


namespace App\Purchases;


class Address
{
    public string $line_one;
    public string $line_two;
    public string $city;
    public string $postal_code;
    public string $notes;

    public function __construct($info)
    {
        $this->line_one = $info['line_one'] ?? '';
        $this->line_two = $info['line_two'] ?? '';
        $this->city = $info['city'] ?? '';
        $this->postal_code = $info['postal_code'] ?? '';
        $this->notes = $info['notes'] ?? '';
    }
}
