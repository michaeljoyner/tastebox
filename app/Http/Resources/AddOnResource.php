<?php

namespace App\Http\Resources;

use App\AddOns\AddOn;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddOnResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'uuid'        => $this->uuid,
            'name'        => $this->name,
            'description' => $this->description,
            'price'       => $this->price,
            'price_formatted'       => "R" . number_format($this->price / 100, 2),
            'image'       => [
                'web'   => $this->getFirstMediaUrl(AddOn::IMAGE, 'web'),
                'thumb' => $this->getFirstMediaUrl(AddOn::IMAGE, 'thumb'),
            ],
            'category'    => AddOnCategoryResource::make($this->whenLoaded('category')),
        ];
    }
}
