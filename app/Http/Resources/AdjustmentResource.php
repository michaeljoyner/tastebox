<?php

namespace App\Http\Resources;

use App\DatePresenter;
use App\Purchases\Adjustment;
use Illuminate\Http\Resources\Json\JsonResource;

class AdjustmentResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'value_in_cents'  => $this->value_in_cents,
            'credit'          => $this->value_in_cents > 0,
            'amount'          => sprintf("R%s", abs($this->value_in_cents / 100)),
            'order_id'        => $this->order_id,
            'reason'          => $this->reason,
            'is_resolved'     => $this->status === Adjustment::STATUS_RESOLVED,
            'status'          => $this->status,
            'created_by'      => $this->created_by,
            'creator'         => $this->creator?->name,
            'resolved_by'     => $this->resolved_by,
            'resolvor'        => $this->resolver?->name,
            'user_id'         => $this->user_id,
            'customer_name'   => $this->customer_name,
            'customer_email'  => $this->customer_email,
            'customer_phone'  => $this->customer_phone,
            'created_at'      => DatePresenter::pretty($this->created_at),
            'resolved_at'     => DatePresenter::pretty($this->resolved_at),
            'resolution_note' => $this->resolution_note,
        ];
    }
}
