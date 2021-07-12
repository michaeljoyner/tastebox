<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function store(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect('/me/home');
    }
}
