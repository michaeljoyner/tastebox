<?php


namespace App\Orders;


use Carbon\Carbon;

class DiscountCodeInfo
{
    public string $code;
    public int $type;
    public Carbon $valid_from;
    public Carbon $valid_until;
    public int $value;
    public int $uses;

    public function __construct($info)
    {
        $this->code = $info['code'] ?? '';
        $this->type = $info['type'];
        $this->valid_from = Carbon::parse($info['valid_from'] ?? null);
        $this->valid_until = Carbon::parse($info['valid_until'] ?? now()->addWeek());
        $this->value = $info['value'] ?? 0;
        $this->uses = $info['uses'] ?? 0;
    }

    public function toArray(): array
    {
        return [
            'code'        => $this->code,
            'type'        => $this->type,
            'valid_from'  => $this->valid_from,
            'valid_until' => $this->valid_until,
            'value'       => $this->value,
            'uses'        => $this->uses,
        ];
    }
}
