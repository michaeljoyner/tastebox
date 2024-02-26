<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FreeRecipeResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'meal_id' => $this->id,
            'name' => $this->name,
        ];
    }
}
