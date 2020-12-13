<?php

namespace App\Http\Controllers;

use App\Purchases\DiscountCode;
use Illuminate\Http\Request;

class DiscountCodeStatusController extends Controller
{
    public function show()
    {
        $code = DiscountCode::for(request('discount_code', ''));

        if(!$code) {
            return [
                'code' => request('discount_code'),
                'is_valid' => false,
                'message' => request('discount_code') . " is not a valid discount code.",
                'type' => null,
                'value' => null,
            ];
        }

            return [
                'code' => $code->code,
                'is_valid' => $code->isValid(),
                'message' => $this->reasonInvalid($code),
                'type' => $code->type === DiscountCode::LUMP ? 'lump' : 'percent',
                'value' => $code->value,
            ];
    }

    private function reasonInvalid(DiscountCode $code): string
    {
        if($code->isValid()) {
            return '';
        }

        return $code->uses > 0 ? "{$code->code} has expired." : "{$code->code} has already been used.";

    }
}
