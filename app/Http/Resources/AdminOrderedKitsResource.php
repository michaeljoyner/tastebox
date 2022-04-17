<?php

namespace App\Http\Resources;

use App\DatePresenter;
use App\Meals\Meal;
use App\Purchases\OrderedKit;
use App\Purchases\OrderedKitPresenter;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminOrderedKitsResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'customer_name'   => $this->order->customerFullname(),
            'address'         => $this->deliveryAddress()->asString(),
            'delivery_date'   => DatePresenter::pretty($this->delivery_date),
            'menu_week'       => $this->menu_week_number,
            'meals'           => $this->meal_summary,
            'status'          => $this->status,
            'is_cancelled'    => $this->status === OrderedKit::STATUS_CANCELED,
            'available_meals' => $this->menu->meals->map(fn(Meal $meal) => [
                'id'   => $meal->id,
                'name' => $meal->name,
            ]),
        ];
    }
}
