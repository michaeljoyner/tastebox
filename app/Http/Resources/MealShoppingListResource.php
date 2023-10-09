<?php

namespace App\Http\Resources;

use App\DatePresenter;
use App\Purchases\ShoppingList;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MealShoppingListResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            'uuid'    => $this->uuid,
            'created' => DatePresenter::pretty($this->created_at),
            'meal_entries' => MealShoppingListEntryResource::collection($this->whenLoaded('entries')),
            'shopping_list' => ShoppingList::fromMealShoppingList($this->resource)->toArray(),
        ];
    }
}
