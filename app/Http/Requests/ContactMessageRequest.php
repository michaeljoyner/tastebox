<?php

namespace App\Http\Requests;

use App\MessageDetails;
use Illuminate\Foundation\Http\FormRequest;

class ContactMessageRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'name'    => ['required'],
            'email'   => ['required_without:phone', 'email', 'nullable'],
            'phone'   => ['required_without:email'],
            'message' => ['required'],
        ];
    }

    public function messageDetails(): MessageDetails
    {
        return new MessageDetails($this->all([
            'name',
            'email',
            'phone',
            'message',
        ]));
    }
}
