<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DiscountCode implements Rule
{
    public string $reason;

    public function __construct()
    {
        //
    }


    public function passes($attribute, $value)
    {
        if($value === null || $value === '') {
            return true;
        }

        $code = \App\Purchases\DiscountCode::for($value);

        if(!$code) {
            $this->reason = "{$value} is not a valid discount code.";
            return false;
        }

        if(!$code->isValid()) {
            $this->reason = $code->uses > 0 ? "{$value} has already been used." : "{$value} has expired";
            return false;
        }

        return true;
    }


    public function message()
    {
        return 'The validation error message.';
    }
}
