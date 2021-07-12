<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailVerificationLinkRequestController extends Controller
{
    public function store()
    {
        request()->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    }
}
