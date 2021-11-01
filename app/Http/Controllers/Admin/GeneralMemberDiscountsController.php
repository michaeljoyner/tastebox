<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberDiscountRequest;
use App\Purchases\MemberDiscount;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GeneralMemberDiscountsController extends Controller
{
    public function store(MemberDiscountRequest $request)
    {
        $tag = Str::uuid()->toString();

        User::members()->each(
            fn (User $user) => $user->awardDiscount($request->codeInfo(), $tag)
        );
    }

    public function update(MemberDiscountRequest $request, $tag)
    {
        MemberDiscount::where('discount_tag', $tag)
            ->get()
            ->each
            ->update($request->codeInfo()->forMember());
    }

    public function delete($tag)
    {
        MemberDiscount::where('discount_tag', $tag)->delete();
    }
}
