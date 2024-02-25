<?php

namespace App\Http\Requests;

use App\Meals\Meal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class MenuFreeRecipesRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'meal_ids'   => ['present', 'array'],
            'meal_ids.*' => ['exists:meals,id']
        ];
    }

    public function meals(): Collection
    {
        return Meal::find($this->meal_ids ?? null);
    }
}
