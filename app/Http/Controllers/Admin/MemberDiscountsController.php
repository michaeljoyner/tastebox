<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountCodeRequest;
use App\User;
use Illuminate\Http\Request;

class MemberDiscountsController extends Controller
{
    public function store(DiscountCodeRequest $request, User $member)
    {
        $member->awardDiscount($request->codeInfo());
    }
}
