<?php

namespace App\Http\Resources;

use App\DatePresenter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderedKitResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_name' => $this->order->customerFullname(),
            'address' => $this->deliveryAddress()->toString(),
            'delivery_date' => DatePresenter::pretty($this->delivery_date),
            'menu_week' => $this->menu_week_number,
            'meals' => $this->meal_summary,
        ];
    }
}
