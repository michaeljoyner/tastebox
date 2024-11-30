<?php

namespace App\Http\Resources;

use App\AddOns\AddOnCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddOnCategoryResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'uuid'        => $this->uuid,
            'name'        => $this->name,
            'description' => $this->description,
            'image'       => [
                'web'   => $this->getFirstMediaUrl(AddOnCategory::IMAGE, 'web'),
                'thumb' => $this->getFirstMediaUrl(AddOnCategory::IMAGE, 'thumb'),
            ],
            'add_ons'     => AddOnResource::collection($this->whenLoaded('addOns')),
        ];
    }
}
