<?php

namespace App\Http\Requests;

use App\DeliveryAddress;
use App\DeliveryArea;
use App\Purchases\Kit;
use App\Purchases\ShoppingBasket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class DeliveryAddressRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'type' => ['required', Rule::in(['address', 'kit'])],
            'delivery_area' => ['required_if:type,address', new Enum(DeliveryArea::class)],
            'delivery_address' => ['required_if:type,address'],
            'kit_id' => ['required_if:type,kit'],
            "apply_to_all_unset" => ['boolean']
        ];
    }

    public function deliveryAddress(): DeliveryAddress
    {
        $area = DeliveryArea::tryFrom($this->delivery_area);

        return new DeliveryAddress($area, $this->delivery_address);
    }

    public function forCustomAddress(): bool
    {
        return $this->type === "address";
    }

    public function toDeliverWithKit(): bool
    {
        return $this->type === "kit";
    }

    public function deliveryKit(): Kit
    {
        $cart = ShoppingBasket::for($this->user);

        return $cart->getKitOrFail($this->kit_id);
    }

    public function forAllUnsetKits(): bool
    {
        return $this->apply_to_all_unset;
    }
}
