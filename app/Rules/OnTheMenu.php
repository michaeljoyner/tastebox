<?php

namespace App\Rules;

use App\Orders\Menu;
use Illuminate\Contracts\Validation\Rule;

class OnTheMenu implements Rule
{

    private Menu $menu;

    public function __construct(Menu $menu, private string $type = 'meal')
    {
        $this->menu = $menu;
    }


    public function passes($attribute, $value)
    {
        if($this->type === 'meal') {
            return $this->menu->meals->contains(fn($meal) => $meal->id === intval($value));
        }

        if($this->type === 'addon') {
            return $this->menu->addOns->contains(fn($addOn) => $addOn->id === intval($value));
        }

        return false;
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
