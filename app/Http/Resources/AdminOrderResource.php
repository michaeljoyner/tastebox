<?php

namespace App\Http\Resources;

use App\DatePresenter;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminOrderResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'date'              => DatePresenter::pretty($this->created_at),
            'customer_fullname' => $this->customerFullname(),
            'price'             => sprintf("R%s", round($this->price_in_cents / 100, 2)),
            'status'            => $this->status,
            'number_of_kits'    => $this->orderedKits()->count(),
            'batch'             => sprintf("Week %s, %s", $this->created_at->week,
                DatePresenter::range($this->created_at->startOfWeek(), $this->created_at->endOfWeek())),
            'kits'              => $this->orderedKits->map(fn ($k) => $k->summarizeForAdmin()),
            'customer'          => $this->customer()->toArray(),
        ];
    }
}
