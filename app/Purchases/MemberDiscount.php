<?php

namespace App\Purchases;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberDiscount extends Model implements Discount
{
    protected $guarded;

    protected $casts = [
        'valid_from' => 'date:Y-m-d',
        'valid_until' => 'date:Y-m-d',
    ];


    public function getCode(): string
    {
        return $this->code ?? '';
    }

    public function getValue(): int
    {
        return $this->value ?? 0;
    }

    public function getType(): int
    {
        return $this->type ?? Discount::LUMP;
    }

    public function isValid(): bool
    {
        return (now()->isBetween($this->valid_from->startOfDay(), $this->valid_until->endOfDay()));
    }

    public function use()
    {
        $this->delete();
    }

    public function discount(int $amount): int
    {
        if($this->type === Discount::LUMP) {
            return max($amount - ($this->value * 100), 0);
        }

        if($this->type === Discount::PERCENTAGE) {
            return intval(round($amount * (100 - $this->value)/100));
        }

        return $amount;
    }
}
