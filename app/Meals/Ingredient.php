<?php

namespace App\Meals;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{

    protected $fillable = ['description'];

    public static function addNew(string $description): Ingredient
    {
        return static::firstOrCreate(['description' => $description]);
    }

    public function toArray()
    {
        if($this->pivot) {
            return [
                'id' => $this->id,
                'description' => $this->description,
                'quantity' => $this->pivot->quantity,
                'in_kit' => $this->pivot->in_kit,
            ];
        }
        return [
            'id' => $this->id,
            'description' => $this->description,
        ];
    }


}
