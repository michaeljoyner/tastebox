<?php

namespace App\Http\Requests;

use App\PhoneNumber;
use App\Purchases\Address;
use App\Purchases\Discount;
use App\Purchases\DiscountCode;
use App\Purchases\MemberDiscount;
use App\Purchases\NullDiscount;
use App\Rules\ForExistingKit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PlaceOrderRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        if (!$this->user()) {
            return [
                'first_name'              => ['required_without:last_name'],
                'last_name'               => ['required_without:first_name'],
                'email'                   => ['required', 'email'],
                'discount_code'           => [new \App\Rules\DiscountCode()],
                'subscribe_to_newsletter' => ['boolean', 'nullable']
            ];
        }

        return [
            'discount_code' => [new \App\Rules\DiscountCode()],
        ];

    }

    public function customerDetails(): array
    {
        if ($this->user()) {
            $profile = $this->user()->profile;

            return [
                'user_id'    => $this->user()->id,
                'first_name' => $profile->first_name,
                'last_name'  => $profile->last_name,
                'email'      => $profile->email,
                'phone'      => $profile->phone,
            ];
        }

        return $this->all('first_name', 'last_name', 'email', 'phone');
    }

    public function addressedKits(Collection $kits): Collection
    {
        return $kits
            ->map(fn($kit) => [
                'kit'     => $kit,
                'address' => $this->addressForKit($kit->id)
            ]);
    }

    private function addressForKit($kit_id)
    {
        $base = $this->delivery ? $this->delivery[array_key_first($this->delivery)] : $this->user()->profile->addressInfo();
        $address = $this->delivery[$kit_id] ?? $base;

        return new Address($address);
    }

    public function discount(): Discount
    {
        if (!$this->discount_code && !$this->member_discount_id) {
            return new NullDiscount();
        }

        $publicDiscount = $this->publicDiscount();
        $memberDiscount = $this->memberDiscount();

        return $publicDiscount->discount(10000) > $memberDiscount->discount(10000) ? $memberDiscount : $publicDiscount;

    }

    private function publicDiscount(): Discount
    {
        return DiscountCode::for($this->discount_code ?? '') ?? new NullDiscount();
    }

    private function memberDiscount(): Discount
    {
        return MemberDiscount::find($this->member_discount_id) ?? new NullDiscount();
    }

    public function allowsNewsletterSignup(): bool
    {
        return !!$this->get('subscribe_to_newsletter', false);
    }

    public function allowsSmsSubscription(): bool
    {
        return $this->get('get_sms_reminder', false) && $this->get('phone', false);
    }

    public function fullName(): string
    {
        if (!$this->first_name) {
            return $this->last_name;
        }

        if (!$this->last_name) {
            return $this->first_name;
        }

        return "{$this->first_name} {$this->last_name}";
    }

    public function firstName()
    {
        return $this->first_name ? $this->first_name : $this->last_name;
    }

    public function phoneNumber()
    {
        return PhoneNumber::from($this->phone);
    }
}
