<?php

namespace App\Purchases;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderedAddOn extends Pivot
{
    public $incrementing = true;

    protected $casts = ['servings' => 'integer'];
}
