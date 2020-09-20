<?php

namespace App\Http\Requests;

use App\Purchases\Address;
use App\Rules\ForExistingKit;
use Illuminate\Foundation\Http\FormRequest;

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
            'delivery.*.postal_code' => ['required'],
        ];
    }

    public function addressForKit($kit_id)
    {
        $address = $this->delivery[$kit_id] ?? $this->delivery[array_key_first($this->delivery)];
        return new Address($address);
    }
}