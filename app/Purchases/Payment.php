<?php

namespace App\Purchases;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'merchant',
        'payment_id',
        'amount_gross',
        'amount_fee',
        'amount_net',
        'item',
        'description',
    ];
}
