<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class CellNumber implements Rule
{

    const ACCEPTED_PREFIXES = ['06', '07', '08'];

    public function __construct()
    {
        //
    }


    public function passes($attribute, $value)
    {
        if(!is_string($value)) {
            return false;
        }

        if (!collect(self::ACCEPTED_PREFIXES)->contains(Str::substr($value, 0, 2))) {
            return false;
        }

        if(strlen($value) !== 10) {
            return false;
        }

        return true;


    }


    public function message()
    {
        return 'Must be a valid SA cell number';
    }
}
