<?php

namespace App\Http\Requests;

use App\Meals\MealPriceTier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CostingRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'cost'        => ['required'],
            'date_costed' => ['required', 'date'],
            'tier'        => ['required', Rule::enum(MealPriceTier::class)],
        ];
    }

    public function costingInfo(): array
    {
        return $this->only([
            'cost',
            'tier',
            'date_costed',
            'note',
        ]);
    }
}
