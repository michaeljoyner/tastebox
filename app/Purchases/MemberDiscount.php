<?php

namespace App\Purchases;

use App\DatePresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberDiscount extends Model implements Discount
{
    protected $guarded;

    protected $casts = [
        'valid_from'  => 'date:Y-m-d',
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
        if ($this->type === Discount::LUMP) {
            return max($amount - ($this->value * 100), 0);
        }

        if ($this->type === Discount::PERCENTAGE) {
            return intval(round($amount * (100 - $this->value) / 100));
        }

        return $amount;
    }

    private function valueAsString(): string
    {
        if ($this->type === static::LUMP) {
            return sprintf("R%.2f", $this->value);
        }

        if ($this->type === static::PERCENTAGE) {
            return "{$this->value}%";
        }

        return 'invalid value';
    }

    public function toArray(): array
    {
        return [
            'id'                 => $this->id,
            'code'               => $this->code,
            'type'               => $this->type === Discount::LUMP ? 'lump' : 'percent',
            'valid_from'         => optional($this->valid_from)->format(DatePresenter::STANDARD),
            'valid_until'        => optional($this->valid_until)->format(DatePresenter::STANDARD),
            'valid_dates'        => DatePresenter::range($this->valid_from, $this->valid_until),
            'is_valid'           => $this->isValid(),
            'value'              => $this->value,
            'value_string'       => $this->valueAsString(),
            'uses'               => $this->uses,
            'is_member_discount' => true,
        ];

    }
}
