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
            'first_name' => ['required_without:last_name'],
            'last_name' => ['required_without:first_name'],
            'email' => ['email', 'nullable'],
            'phone' => [],
            'sms_reminders' => ['boolean'],
            'email_reminders' => ['boolean'],
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
            'sms_reminders',
            'email_reminders',
        ]));
    }
}
