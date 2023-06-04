<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'position' => $this->getCustomProperty('position'),
            'thumb'    => $this->getUrl('thumb'),
            'web'      => $this->getUrl('web'),
            'src'      => $this->getUrl('web'),
            'original' => $this->getUrl(),
        ];
    }
}
