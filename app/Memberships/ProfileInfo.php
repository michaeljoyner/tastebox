<?php


namespace App\Memberships;


class ProfileInfo
{

    public string $first_name;
    public string $last_name;
    public string $email;
    public string $phone;
    public string $address_line_one;
    public string $address_line_two;
    public string $address_city;

    public function __construct(array $info)
    {
        $this->first_name = $info['first_name'] ?? '';
        $this->last_name = $info['last_name'] ?? '';
        $this->email = $info['email'] ?? '';
        $this->phone = $info['phone'] ?? '';
        $this->address_line_one = $info['address_line_one'] ?? '';
        $this->address_line_two = $info['address_line_two'] ?? '';
        $this->address_city = $info['address_city'] ?? '';
    }

    public function toArray(): array
    {
        return [
            'first_name'       => $this->first_name,
            'last_name'        => $this->last_name,
            'email'            => $this->email,
            'phone'            => $this->phone,
            'address_line_one' => $this->address_line_one,
            'address_line_two' => $this->address_line_two,
            'address_city'     => $this->address_city,
        ];
    }
}
