<?php

namespace App\Meals;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Costing extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date_costed' => 'datetime',
        'tier'        => MealPriceTier::class,
    ];
}
