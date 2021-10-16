<?php

namespace App\Http\Requests;

use App\Orders\DiscountCodeInfo;
use App\Purchases\Discount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberDiscountRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'code'       => ['required', 'alpha_num'],
            'valid_from' => ['date', 'nullable'],
            'valid_until' => ['date', 'after_or_equal:valid_from', 'nullable'],
            'type' => [Rule::in([Discount::PERCENTAGE, Discount::LUMP])],
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
        ]));
    }
}
