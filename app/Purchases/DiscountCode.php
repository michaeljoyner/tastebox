<?php

namespace App\Purchases;

use App\Orders\DiscountCodeInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    const LUMP = 1;
    const PERCENTAGE = 2;

    protected $fillable = [
        'code',
        'type',
        'valid_from',
        'valid_until',
        'value',
        'uses',
    ];

    protected $casts = [
        'valid_from' => 'date:Y-m-d',
        'valid_until' => 'date:Y-m-d',
    ];

    public static function new(DiscountCodeInfo $codeInfo): self
    {
        return static::create($codeInfo->toArray());
    }
}
