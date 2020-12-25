<?php

namespace App\Http\Requests;

use App\Purchases\Address;
use App\Purchases\Discount;
use App\Purchases\DiscountCode;
use App\Purchases\NullDiscount;
use App\Rules\ForExistingKit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class PlaceOrderRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'first_name' => ['required_without:last_name'],
            'last_name' => ['required_without:first_name'],
            'email' => ['required', 'email'],
            'delivery' => ['required', 'array'],
            'delivery.*' => [new ForExistingKit()],
            'delivery.*.line_one' => ['required'],
            'delivery.*.city' => ['required'],
            'discount_code' => [new \App\Rules\DiscountCode()],
            'subscribe_to_newsletter' => ['boolean', 'nullable']
        ];
    }

    public function customerDetails(): array
    {
        return $this->all('first_name', 'last_name', 'email', 'phone');
    }

    public function adressedKits(Collection $kits): Collection
    {
        return $kits
            ->map(fn ($kit) => [
                'kit' => $kit,
                'address' => $this->addressForKit($kit->id)
            ]);
    }

    private function addressForKit($kit_id)
    {
        $address = $this->delivery[$kit_id] ?? $this->delivery[array_key_first($this->delivery)];
        return new Address($address);
    }

    public function discount(): Discount
    {
        if(!$this->discount_code) {
            return new NullDiscount();
        }

        return DiscountCode::for($this->discount_code) ?? new NullDiscount();

    }

    public function allowsNewsletterSignup(): bool
    {
        return !! $this->get('subscribe_to_newsletter', false);
    }

    public function fullName(): string
    {
        if(!$this->first_name) {
            return $this->last_name;
        }

        if(!$this->last_name) {
            return $this->first_name;
        }

        return "{$this->first_name} {$this->last_name}";
    }
}
