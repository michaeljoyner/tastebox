<?php

namespace App\Http\Requests;

use App\Meals\MealPriceTier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'classifications' => ['array'],
            'classifications.*' => ['integer', 'exists:classifications,id'],
            'price_tier' => ['required', Rule::enum(MealPriceTier::class)]
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
            'price_tier'
        ]);

        $classifications = collect($this->classifications)->map(fn ($c) => intval($c))->all();

        return [
            'meal_attributes' => $meal,
            'classifications' => $classifications,
        ];
    }
}
