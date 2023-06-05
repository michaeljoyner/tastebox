<?php

namespace App\Http\Resources;

use App\DatePresenter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberOrderResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'date'              => DatePresenter::pretty($this->created_at),
            'created_ts'        => $this->created_at->timestamp,
            'is_paid'           => $this->is_paid,
            'customer_fullname' => $this->customerFullname(),
            'price'             => sprintf("R%s", round($this->price_in_cents / 100, 2)),
            'status'            => $this->status,
            'kits'              => OrderedKitResource::collection($this->whenLoaded('orderedKits')),
            'batch'             => sprintf("Week %s, %s", $this->created_at->week,
                DatePresenter::range($this->created_at->startOfWeek(), $this->created_at->endOfWeek()))
        ];
    }
}
