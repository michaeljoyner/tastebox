<?php

namespace App\Http\Requests;

use App\DeliveryArea;
use App\Memberships\ProfileInfo;
use App\Rules\CellNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

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
            'email' => [
                'email',
                'nullable',
                Rule::unique('users', 'email')->ignore($this->user()),
//                Rule::unique('member_profiles', 'email')->ignore($this->user()->profile),
            ],
            'phone' => [new CellNumber(), 'nullable'],
            'sms_reminders' => ['boolean'],
            'email_reminders' => ['boolean'],
            'address_city' => [new Enum(DeliveryArea::class)],
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
