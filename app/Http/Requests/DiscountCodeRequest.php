<?php

namespace App\Http\Requests;

use App\Orders\DiscountCodeInfo;
use App\Purchases\Discount;
use App\Purchases\DiscountCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DiscountCodeRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'code'       => ['required', Rule::unique('discount_codes', 'code')->ignore($this->route('code'))],
            'valid_from' => ['date', 'nullable'],
            'valid_until' => ['date', 'after_or_equal:valid_from', 'nullable'],
            'type' => [Rule::in([Discount::PERCENTAGE, Discount::LUMP])],
            'uses' => ['integer', 'min:1', 'nullable'],
            'value' => ['integer', 'min:1', 'nullable'],
        ];
    }

    public function codeInfo(): DiscountCodeInfo
    {
        return new DiscountCodeInfo($this->all([
            'code',
            'type',
            'valid_from',
            'valid_until',
            'value',
            'uses',
        ]));
    }
}
