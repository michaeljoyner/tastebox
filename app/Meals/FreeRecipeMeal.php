<?php

namespace App\Meals;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FreeRecipeMeal extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function meal(): BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }

}
