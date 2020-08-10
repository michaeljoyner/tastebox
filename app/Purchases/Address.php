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

    public function toArray(): array
    {
        return [
            'line_one'    => $this->line_one,
            'line_two'    => $this->line_two,
            'city'        => $this->city,
            'postal_code' => $this->postal_code,
            'notes'       => $this->notes,
        ];
    }
}
