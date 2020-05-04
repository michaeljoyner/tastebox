<?php

namespace App\Meals;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Meal extends Model
{
    protected $fillable = [
        'unique_id',
        'name',
        'description',
        'allergens',
        'prep_time',
        'cook_time',
        'instructions',
        'serving_energy',
        'serving_carbs',
        'serving_fat',
        'serving_protein'
    ];

    protected $casts = ['unique_id' => 'string'];

    public static function createNew()
    {
        return static::create([
            'unique_id' => static::generateUniqueId(),
        ]);
    }

    public function updateWithFormData($form_data)
    {
        $this->update($form_data['meal_attributes']);
        $this->ingredients()->sync($form_data['ingredients']);
    }

    public static function generateUniqueId()
    {
        return Str::of(Str::random(10))
            ->lower()
            ->replaceMatches('/[^a-z]{1}/', "")
            ->substr(0,6);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class)
            ->using(MealIngredient::class)
            ->withPivot(['quantity', 'in_kit']);
    }

    public function customerIngredients()
    {
        return $this->belongsToMany(Ingredient::class)
            ->wherePivot('in_kit', 0);
    }

    public function kitIngredients()
    {
        return $this->belongsToMany(Ingredient::class)
                    ->wherePivot('in_kit', 1);
    }

    public function asArrayForAdmin()
    {
        return [
            'id'                   => $this->id,
            'unique_id'            => $this->unique_id,
            'name'                 => $this->name,
            'description'          => $this->description,
            'allergens'            => $this->allergens,
            'prep_time'            => $this->prep_time,
            'cook_time'            => $this->cook_time,
            'instructions'         => $this->instructions,
            'serving_energy'       => $this->serving_energy,
            'serving_carbs'        => $this->serving_carbs,
            'serving_fat'          => $this->serving_fat,
            'serving_protein'      => $this->serving_protein,
            'ingredients' => $this->ingredients->map->toArray()->all(),
        ];
    }
}
