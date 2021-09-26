<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $guarded = [];

    public function toArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'author' => $this->author,
            'created_at' => DatePresenter::pretty($this->created_at),
            'timestamp' => $this->created_at->unix(),
        ];
    }
}
