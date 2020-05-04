<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MealFormRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'prep_time' => ['integer'],
            'cook_time' => ['integer'],
            'serving_energy' => ['integer'],
            'serving_carbs' => ['integer'],
            'serving_protein' => ['integer'],
            'serving_fat' => ['integer'],
            'customer_ingredients.*' => ['exists:ingredients,id'],
            'kit_ingredients.*' => ['exists:ingredients,id'],
        ];
    }

    public function formData()
    {
        $meal = $this->only([
            'name',
            'description',
            'allergens',
            'prep_time',
            'cook_time',
            'instructions',
            'serving_energy',
            'serving_carbs',
            'serving_fat',
            'serving_protein',
        ]);

        $ingredients = collect($this->ingredients)
            ->mapWithKeys(fn ($ingredient) => [
                $ingredient['id'] => [
                    'quantity' => $ingredient['quantity'],
                    'in_kit' => $ingredient['in_kit'],
                ]
            ]);

        return [
            'meal_attributes' => $meal,
            'ingredients' => $ingredients->all(),
        ];
    }
}
