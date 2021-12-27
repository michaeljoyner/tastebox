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

    protected $casts = [
        'resolved_at' => 'datetime'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public static function new(
        int    $original_value,
        int    $new_value,
        Order  $order,
        string $reason,
        User   $creator
    ): self {
        $value = $new_value - $original_value;

        $customer = $order->customer();

        return self::create([
            'value_in_cents' => $value,
            'order_id'       => $order->id,
            'reason'         => $reason,
            'status'         => $value === 0 ? self::STATUS_RESOLVED : self::STATUS_UNRESOLVED,
            'created_by'     => $creator->id,
            'resolved_by'    => $value === 0 ? $creator->id : null,
            'user_id'        => $order->user_id,
            'customer_name'  => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => $customer->phone,
        ]);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function resolve(User $resolver, ?string $note = '')
    {
        $this->update([
            'status'          => self::STATUS_RESOLVED,
            'resolved_at'     => now(),
            'resolved_by'     => $resolver->id,
            'resolution_note' => $note,
        ]);
    }
}
