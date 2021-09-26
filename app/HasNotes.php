<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasNotes
{

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }

    public function addNote(string $title, string $body, string $author = '')
    {
        $this->notes()->create([
            'title' => $title,
            'body' => $body,
            'author' => $author,
        ]);
    }
}
