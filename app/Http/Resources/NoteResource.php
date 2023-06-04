<?php

namespace App\Http\Resources;

use App\DatePresenter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'body'       => $this->body,
            'author'     => $this->author,
            'created_at' => DatePresenter::pretty($this->created_at),
            'timestamp'  => $this->created_at->unix(),
        ];
    }
}
