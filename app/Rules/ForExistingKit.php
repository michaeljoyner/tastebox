<?php

namespace App\Rules;

use App\Purchases\ShoppingBasket;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class ForExistingKit implements Rule
{

    public function __construct()
    {
        //
    }


    public function passes($attribute, $value)
    {
        $basket = ShoppingBasket::for(request()->user());
        return $basket->hasKit(Str::after($attribute, 'delivery.'));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'One of your delivery addresses is for a non-existing kit';
    }
}
