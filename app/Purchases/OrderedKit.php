<?php

namespace App\Purchases;

use App\Meals\Meal;
use Illuminate\Database\Eloquent\Model;

class OrderedKit extends Model
{
    protected $fillable = [
        'kit_id',
        'menu_id',
        'menu_week_number',
        'delivery_date',
        'meal_summary',
        'line_one',
        'line_two',
        'city',
        'postal_code',
        'delivery_notes',
    ];

    protected $dates = ['delivery_date'];

    protected $casts = ['meal_summary' => 'array'];

    public function meals()
    {
        return $this->belongsToMany(Meal::class)
                    ->using(OrderedMeal::class)
                    ->withPivot('servings');
    }
}
