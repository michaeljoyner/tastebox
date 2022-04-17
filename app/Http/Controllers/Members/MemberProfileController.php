<?php

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberProfileRequest;
use Illuminate\Http\Request;

class MemberProfileController extends Controller
{

    public function edit()
    {
        return view('members.profile.edit', ['profile' => request()->user()->profile->toArray()]);
    }

    public function update(MemberProfileRequest $request)
    {
        $profile = $request->user()->profile;
        $profile->update($request->profileInfo()->toArray());

        $request->user()->update(['email' => $profile->fresh()->email]);

        return redirect('/me/home')
            ->with('toast', ['type' => 'success', 'text' => 'Your information has been updated']);
    }
}
