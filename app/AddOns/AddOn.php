<?php

namespace App\AddOns;

use App\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AddOn extends Model
{
    use HasUuid;

    protected $guarded = [];

    public function category(): BelongsTo
    {
        return $this->belongsTo(AddOnCategory::class, 'add_on_category_id');
    }
}
