<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SignUpRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'confirmed'],
        ];
    }
}
