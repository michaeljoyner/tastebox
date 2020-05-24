<?php

namespace App\Meals;

use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
