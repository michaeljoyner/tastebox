<?php


namespace App\Meals;


class IngredientList
{
    public array $ingredients;

    public function __construct($ingredients)
    {
        $this->ingredients = $ingredients;
    }
}
