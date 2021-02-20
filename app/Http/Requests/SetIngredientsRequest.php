<?php

namespace App\Http\Requests;

use App\Meals\IngredientList;
use Illuminate\Foundation\Http\FormRequest;

class SetIngredientsRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'ingredients'          => ['required', 'array'],
            'ingredients.*'        => ['array'],
            'ingredients.*.id'     => ['required', 'exists:ingredients,id'],
            'ingredients.*.in_kit' => ['boolean'],
        ];
    }

    public function ingredientsList(): IngredientList
    {
        $ingredients = collect($this->ingredients)
            ->map(fn($ingredient) => [
                'id'       => $ingredient['id'],
                'quantity' => $ingredient['quantity'],
                'in_kit'   => $ingredient['in_kit'],
                'form'     => $ingredient['form'] ?? '',
                'group' => $ingredient['group'] ?? '',
                'bundled' => $ingredient['bundled'] ?? false,
            ]);

        return new IngredientList($ingredients->all());
    }
}
