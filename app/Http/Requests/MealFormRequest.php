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
            'prep_time' => ['nullable', 'integer'],
            'cook_time' => ['nullable', 'integer'],
            'serving_energy' => ['nullable', 'integer'],
            'serving_carbs' => ['nullable', 'integer'],
            'serving_protein' => ['nullable', 'integer'],
            'serving_fat' => ['nullable', 'integer'],
            'customer_ingredients.*' => ['exists:ingredients,id'],
            'kit_ingredients.*' => ['exists:ingredients,id'],
            'classifications' => ['array'],
            'classifications.*' => ['integer', 'exists:classifications,id'],
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

        $classifications = collect($this->classifications)->map(fn ($c) => intval($c))->all();

        return [
            'meal_attributes' => $meal,
            'ingredients' => $ingredients->all(),
            'classifications' => $classifications,
        ];
    }
}
