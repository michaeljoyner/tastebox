<?php

namespace App;

use App\Meals\Meal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MealShoppingListEntry extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function meal(): BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }
}
