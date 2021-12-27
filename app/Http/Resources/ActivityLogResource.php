<?php

namespace App\Http\Resources;

use App\DatePresenter;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => DatePresenter::prettyDateTime($this->created_at),
            'activity' => $this->activity,
            'actor' => $this->actor,
            'url' => $this->url,
        ];
    }
}
