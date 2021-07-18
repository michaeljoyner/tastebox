<?php

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberProfileRequest;
use Illuminate\Http\Request;

class MemberProfileController extends Controller
{
    public function update(MemberProfileRequest $request)
    {
        $request->user()->profile->update($request->profileInfo()->toArray());
    }
}
