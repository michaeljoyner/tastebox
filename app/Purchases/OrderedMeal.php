<?php


namespace App\Purchases;


use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderedMeal extends Pivot
{
    public $incrementing = true;

    protected $casts = ['servings' => 'integer'];
}
