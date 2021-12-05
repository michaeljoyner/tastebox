<?php

namespace App\Purchases;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Adjustment extends Model
{
    const STATUS_RESOLVED = 'resolved';
    const STATUS_UNRESOLVED = 'unresolved';
    protected $guarded = [];

    public static function new(
        int    $original_value,
        int    $new_value,
        Order  $order,
        string $reason,
        User   $creator
    ): self {
        $value = $new_value - $original_value;

        return self::create([
            'value_in_cents' => $value,
            'order_id'       => $order->id,
            'reason'         => $reason,
            'status'         => $value === 0 ? self::STATUS_RESOLVED : self::STATUS_UNRESOLVED,
            'created_by'     => $creator->id,
            'resolved_by'    => $value === 0 ? $creator->id : null,
        ]);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
