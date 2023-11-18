<?php

namespace App\Http\Resources;

use App\DatePresenter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CostingResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'cost'        => $this->cost,
            'tier'        => $this->tier->description(),
            'tier_value'  => $this->tier->value,
            'note'        => $this->note,
            'date_costed' => DatePresenter::standard($this->date_costed),
            'date_costed_pretty' => DatePresenter::pretty($this->date_costed),
        ];
    }
}
