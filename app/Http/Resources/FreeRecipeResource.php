<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FreeRecipeResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'meal_id' => $this->unique_id,
            'name' => $this->name,
            'image'        => $this->titleImage('web'),
            'description'  => $this->description,
            'ingredients'  => $this->ingredients,
            'instructions' => $this->instructions,
            'public_notes' => $this->public_recipe_notes,
            'cooking_time' => ($this->cook_time + $this->prep_time) . "mins",
            'categories'   => $this->classifications,
        ];
    }
}
