<?php

namespace App\Memberships;

use App\DatePresenter;
use App\User;

class MemberPresenter
{

    public static function forAdmin(User $member): array
    {
        return [
            'id' => $member->id,
            'username' => $member->name,
            'full_name' => $member->profile->full_name,
            'first_name' => $member->profile->first_name,
            'last_name' => $member->profile->last_name,
            'phone' => $member->profile->phone,
            'email' => $member->profile->email,
            'address' => $member->profile->formattedAddress(),
            'location' => $member->profile->address_city,
            'member_since' => DatePresenter::pretty($member->profile->created_at),
            'signed_up' => $member->profile->created_at->diffForHumans(),
            'orders' => $member->orders,
            'discounts' => $member->discounts,
        ];
    }
}
