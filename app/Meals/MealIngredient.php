<?php


namespace App\Meals;


use Illuminate\Database\Eloquent\Relations\Pivot;

class MealIngredient extends Pivot
{
    public $incrementing = true;

    protected $casts = ['in_kit' => 'boolean', 'bundled' => 'boolean'];

    protected $fillable = ['in_kit', 'quantity', 'position', 'group', 'form', 'bundled'];
}
