<?php

namespace App;

class DeliveryAddress
{
    public function __construct(public DeliveryArea $area, public string $address = '')
    {
    }

    public static function for(?User $user): self
    {
        if(!$user) {
            return new self(DeliveryArea::NOT_SET);
        }

        $area = DeliveryArea::tryFrom($user->profile->address_city) ?? DeliveryArea::NOT_SET;
        $address_parts = collect([$user->profile->address_line_one, $user->profile->address_line_two])
            ->filter(fn ($line) => !!$line);
        $address = $address_parts->count() ? $address_parts->join(", ") : "";

        return new self($area, $address);
    }

    public function isSameAs(DeliveryAddress $address): bool
    {
        return ($this->area === $address->area) && ($this->address === $address->address);
    }

    public static function fake(): self
    {
        return new self(DeliveryArea::NOT_SET, "");
    }

    public function toArray(): array
    {
        return [
            'line_one'    => $this->address,
            'line_two'    => '',
            'city'        => $this->area->value,
            'postal_code' => '',
            'notes'       => '',
        ];
    }

    public function toString(): string
    {
        return sprintf("%s [%s]", $this->address, $this->area->description());
    }
}
