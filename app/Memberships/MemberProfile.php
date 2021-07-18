<?php

namespace App\Memberships;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberProfile extends Model
{
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function addressInfo(): array
    {
        return [
            'line_one' => $this->address_line_one,
            'line_two' => $this->address_line_two,
            'city'     => $this->address_city,
        ];
    }
}
