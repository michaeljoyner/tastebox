<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IngredientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $base = [
            'id' => $this->id,
            'description' => $this->description,
        ];

        if($this->pivot) {
            $base = array_merge(
                $base,
                [
                    'meal_ingredient_id' => $this->pivot->id,
                    'quantity' => $this->pivot->quantity,
                    'form' => $this->pivot->form,
                    'in_kit' => $this->pivot->in_kit,
                    'position' => $this->pivot->position,
                    'group' => $this->pivot->group ?? 'main',
                    'bundled' => $this->pivot->bundled ?? false,
                ]
            );
        }

        return $base;
    }
}
