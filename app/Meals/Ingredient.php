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

    public function scopeUnused($query)
    {
        return $query->whereDoesntHave('meals');
    }

    public function meals()
    {
        return $this->belongsToMany(Meal::class);
    }

    public function toArray()
    {
        if($this->pivot) {
            return [
                'id' => $this->id,
                'description' => $this->description,
                'quantity' => $this->pivot->quantity,
                'form' => $this->pivot->form,
                'in_kit' => $this->pivot->in_kit,
                'position' => $this->pivot->position,
                'group' => $this->pivot->group ?? 'main',
                'bundled' => $this->pivot->bundled ?? false,
            ];
        }
        return [
            'id' => $this->id,
            'description' => $this->description,
        ];
    }


}
