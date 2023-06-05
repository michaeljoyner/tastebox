<?php

namespace App\Http\Resources;

use App\DatePresenter;
use App\Memberships\MemberPresenter;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminMemberResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'username'     => $this->name,
            'full_name'    => $this->profile->full_name,
            'first_name'   => $this->profile->first_name,
            'last_name'    => $this->profile->last_name,
            'phone'        => $this->profile->phone,
            'email'        => $this->profile->email,
            'address'      => $this->profile->formattedAddress(),
            'location'     => $this->profile->deliveryAddress()->area->description(),
            'member_since' => DatePresenter::pretty($this->profile->created_at),
            'signed_up'    => $this->profile->created_at->diffForHumans(),
            'orders' => MemberOrderResource::collection(
                $this->whenLoaded('orders')
            ),
            'discounts'    => MemberDiscountResource::collection($this->discounts),
            'verified' => !!$this->email_verified_at,
            'profile_complete' => $this->profile?->isComplete(),
        ];
    }
}
