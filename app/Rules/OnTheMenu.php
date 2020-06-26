<?php

namespace App\Rules;

use App\Orders\Menu;
use Illuminate\Contracts\Validation\Rule;

class OnTheMenu implements Rule
{

    private Menu $menu;

    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }


    public function passes($attribute, $value)
    {
        return $this->menu->meals->contains(fn($meal) => $meal->id === intval($value));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The meal is not available of the menu';
    }
}
