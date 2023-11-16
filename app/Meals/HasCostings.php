<?php

namespace App\Meals;

use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasCostings
{

    public function costings(): HasMany
    {
        return $this->hasMany(Costing::class);
    }
    public function addCosting(array $costing_info)
    {
        return $this->costings()->create($costing_info);
    }
}
