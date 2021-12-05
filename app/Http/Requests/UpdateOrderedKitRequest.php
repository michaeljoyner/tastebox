<?php

namespace App\Http\Requests;

use App\Purchases\KitMealSummary;
use App\Rules\OnTheMenu;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class UpdateOrderedKitRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'meals' => ['required', 'array'],
            'reason' => ['required'],
            'meals.*' => ['array'],
            'meals.*.id' => ['required', 'exists:meals,id', new OnTheMenu($this->route()->kit->menu)],
            'meals.*.servings' => ['required', 'integer', 'min:1'],
        ];
    }

    public function meals(): KitMealSummary
    {
        return new KitMealSummary(collect($this->meals));
    }
}
