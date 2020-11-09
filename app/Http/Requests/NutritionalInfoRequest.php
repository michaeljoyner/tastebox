<?php

namespace App\Http\Requests;

use App\Meals\NutritionalInfo;
use Illuminate\Foundation\Http\FormRequest;

class NutritionalInfoRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'serving_energy' => ['integer', 'nullable'],
            'serving_fat' => ['integer', 'nullable'],
            'serving_carbs' => ['integer', 'nullable'],
            'serving_protein' => ['integer', 'nullable'],
        ];
    }

    public function nutritionalInfo(): NutritionalInfo
    {
        return new NutritionalInfo($this->all([
            'serving_energy',
            'serving_carbs',
            'serving_fat',
            'serving_protein',
        ]));
    }
}
