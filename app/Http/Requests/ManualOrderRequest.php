<?php

namespace App\Http\Requests;

use App\DeliveryAddress;
use App\DeliveryArea;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ManualOrderRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email'],
            'line_one' => ['required'],
            'city' => ['required', new Enum(DeliveryArea::class)],
            'meals' => ['required', 'array'],
            'meals.*.id' => ['required', 'exists:meals,id'],
            'meals.*.servings' => ['required', 'integer', Rule::in([1,2,4])]
        ];
    }

    public function deliveryAddress(): DeliveryAddress
    {
        return new DeliveryAddress(
            DeliveryArea::tryFrom($this->city) ?? DeliveryArea::NOT_SET,
            $this->line_one,
        );
    }
}
