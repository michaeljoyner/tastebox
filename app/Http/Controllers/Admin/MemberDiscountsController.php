<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountCodeRequest;
use App\Http\Requests\MemberDiscountRequest;
use App\Purchases\MemberDiscount;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MemberDiscountsController extends Controller
{
    public function store(MemberDiscountRequest $request, User $member)
    {
        $member->awardDiscount($request->codeInfo());
    }

    public function update(MemberDiscountRequest $request, MemberDiscount $discount)
    {
        abort_if($discount->discount_tag, Response::HTTP_FORBIDDEN);
        $discount->update($request->codeInfo()->forMember());
    }

    public function delete(MemberDiscount $discount)
    {
        $discount->delete();
    }
}
