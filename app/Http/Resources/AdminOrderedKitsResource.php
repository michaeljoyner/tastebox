<?php

namespace App\Http\Resources;

use App\Purchases\OrderedKitPresenter;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminOrderedKitsResource extends JsonResource
{

    public function toArray($request)
    {
        return OrderedKitPresenter::forAdmin($this->resource);
    }
}
