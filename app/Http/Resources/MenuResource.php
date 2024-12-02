<?php

namespace App\Http\Resources;

use App\DatePresenter;
use App\Orders\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id,
            'can_order'              => $this->can_order,
            'orders_close_on'        => DatePresenter::standard($this->ordersCloseDate()),
            'orders_close_on_pretty' => DatePresenter::pretty($this->ordersCloseDate()),
            'current_from_date'      => $this->current_from->format('Y-m-d'),
            'current_from_pretty'    => $this->current_from->format('jS M, Y'),
            'current_to_date'        => $this->current_to->format('Y-m-d'),
            'current_to_pretty'      => $this->current_to->format('jS M, Y'),
            'current_range_pretty'   => DatePresenter::range($this->current_from, $this->current_to),
            'delivery_from_date'     => $this->delivery_from->format('Y-m-d'),
            'delivery_from_pretty'   => $this->delivery_from->format('jS M, Y'),
            'week_number'            => $this->current_from->week,
            'is_current'             => $this->isCurrent(),
            'status'                 => Menu::UPCOMING,
            'meals'                  => $this->meals->map->asArrayForAdmin()->all(),
            'free_recipe_meals'      => $this->freeRecipeMeals->map(fn($m) => $m->meal->asArrayForAdmin()),
            'add_ons'                => AddOnResource::collection($this->whenLoaded('addOns')),
        ];
    }
}
