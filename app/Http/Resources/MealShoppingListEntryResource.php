<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MealShoppingListEntryResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'servings' => $this->servings,
            'meal' => AdminMealResource::make($this->whenLoaded('meal')),
        ];
    }
}
