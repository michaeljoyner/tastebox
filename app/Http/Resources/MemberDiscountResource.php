<?php

namespace App\Http\Resources;

use App\DatePresenter;
use App\Purchases\Discount;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberDiscountResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'                 => $this->id,
            'discount_tag'       => $this->discount_tag,
            'code'               => $this->code,
            'valid_dates'        => DatePresenter::range($this->valid_from, $this->valid_until),
            'valid_from'         => DatePresenter::standard($this->valid_from),
            'valid_until'        => DatePresenter::standard($this->valid_until),
            'type'               => $this->type,
            'value'              => $this->value,
            'summary'            => $this->summarize($this),
            'is_member_discount' => true,
        ];
    }

    private function summarize($discount): string
    {
        return match ($discount->type) {
            Discount::LUMP => "R{$discount->value} off",
            Discount::PERCENTAGE => "{$discount->value}% off",
            default => "unknown"
        };
    }
}
