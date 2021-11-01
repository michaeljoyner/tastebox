<?php

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemberPasswordController extends Controller
{
    public function update()
    {
        request()->validate([
            'current_password' => ['current_password'],
            'password' => ['required', 'confirmed', 'min:8']
        ]);
        request()->user()->resetPassword(request('password'));

        return redirect('/me/home')
            ->with('toast', ['type' => 'success', 'text' => 'Your password has been reset']);
    }
}
