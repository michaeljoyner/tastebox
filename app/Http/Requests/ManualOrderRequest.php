<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ManualOrderRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email'],
            'line_one' => ['required'],
            'city' => ['required'],
            'meals' => ['required', 'array'],
            'meals.*.id' => ['required', 'exists:meals,id'],
            'meals.*.servings' => ['required', 'integer', Rule::in([1,2,4])]
        ];
    }
}
