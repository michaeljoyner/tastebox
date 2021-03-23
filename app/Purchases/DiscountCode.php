<?php

namespace App\Purchases;

use App\DatePresenter;
use App\Orders\DiscountCodeInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model implements Discount
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
        'valid_from'  => 'date:Y-m-d',
        'valid_until' => 'date:Y-m-d',
    ];

    public static function for(string $code): ?self
    {
        return static::where('code', $code)->first();
    }

    public static function new(DiscountCodeInfo $codeInfo): self
    {
        return static::create($codeInfo->toArray());
    }

    public function toArray(): array
    {
        return [
            'id'           => $this->id,
            'code'         => $this->code,
            'type'         => $this->type,
            'valid_from'   => optional($this->valid_from)->format(DatePresenter::STANDARD),
            'valid_until'  => optional($this->valid_until)->format(DatePresenter::STANDARD),
            'valid_dates'  => DatePresenter::range($this->valid_from, $this->valid_until),
            'is_valid'     => $this->isValid(),
            'value'        => $this->value,
            'value_string' => $this->valueAsString(),
            'uses'         => $this->uses,
        ];

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
        return $this->type ?? static::LUMP;
    }

    public function isValid(): bool
    {
        return (now()->isBetween($this->valid_from->startOfDay(), $this->valid_until->endOfDay())) && $this->uses > 0;
    }

    public function use()
    {
        $this->uses = $this->uses - 1;
        $this->save();
    }

    public function discount(int $amount): int
    {
        if($this->type === static::LUMP) {
            return max($amount - ($this->value * 100), 0);
        }

        if($this->type === static::PERCENTAGE) {
            return intval(round($amount * (100 - $this->value)/100));
        }

        return $amount;
    }
}
