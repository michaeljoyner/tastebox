<?php

namespace App\Memberships;

use App\DeliveryAddress;
use App\DeliveryArea;
use App\Purchases\Address;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberProfile extends Model
{
    protected $guarded = [];

    protected $casts = [
        'sms_reminders' => 'boolean',
        'email_reminders' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute(): string
    {
        return collect([$this->first_name, $this->last_name])
            ->map(fn($name) => trim($name))
            ->filter(fn($name) => !!$name)
            ->join(" ");
    }

    public function addressInfo(): array
    {
        return [
            'line_one' => $this->address_line_one,
            'line_two' => $this->address_line_two,
            'city'     => $this->address_city,
        ];
    }

    public function deliveryAddress(): DeliveryAddress
    {
        return new DeliveryAddress(
            DeliveryArea::tryFrom($this->address_city) ?? DeliveryArea::NOT_SET,
            $this->address_line_one,
        );
    }

    public function isComplete(): bool
    {
        if (!$this->email && !$this->phone) {
            return false;
        }

        if (!$this->address_line_one || !$this->address_city) {
            return false;
        }

        return true;
    }

    public function formattedAddress(): string
    {
        return $this->deliveryAddress()->toString();
    }

    public function toArray()
    {
        return [
            'first_name'       => $this->first_name,
            'last_name'        => $this->last_name,
            'email'            => $this->email,
            'phone'            => $this->phone,
            'address_line_one' => $this->address_line_one,
            'address_line_two' => $this->address_line_two,
            'address_city'     => $this->address_city,
            'is_complete'      => $this->isComplete(),
            'full_address'     => $this->formattedAddress(),
            'sms_reminders'    => $this->sms_reminders,
            'email_reminders'  => $this->email_reminders,
        ];
    }
}
