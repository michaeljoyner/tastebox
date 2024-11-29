<?php

namespace App\AddOns;

use App\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AddOnCategory extends Model
{
    use HasUuid;

    protected $guarded = [];

    public function addOns(): HasMany
    {
        return $this->hasMany(AddOn::class);
    }
}
