<?php

namespace App\Http\Requests;

use App\Memberships\ProfileInfo;
use Illuminate\Foundation\Http\FormRequest;

class MemberProfileRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            //
        ];
    }

    public function profileInfo(): ProfileInfo
    {
        return new ProfileInfo($this->only([
            'first_name',
            'last_name',
            'email',
            'phone',
            'address_line_one',
            'address_line_two',
            'address_city',
        ]));
    }
}
