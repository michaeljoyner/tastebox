<?php


namespace App\Purchases;


class Customer
{

    public string $name;
    public string $email;
    public string $phone;

    public function __construct(string $name, string $email, string $phone = '')
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
    }

    public function toArray(): array
    {
        return [
            'name'  => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }
}
