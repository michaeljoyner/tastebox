<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignUpRequest;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistrationsController extends Controller
{
    public function store(SignUpRequest $request)
    {

        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
        ]);

        event(new Registered($user));

        auth()->login($user);

        return redirect('/me/home');
    }
}
