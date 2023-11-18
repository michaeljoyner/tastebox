<?php

namespace App\Meals;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Costing extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date_costed' => 'datetime',
        'tier'        => MealPriceTier::class,
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('latest', fn (Builder $q) => $q->latest('date_costed'));
    }

    public function meal(): BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }
}
