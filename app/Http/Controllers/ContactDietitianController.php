<?php

namespace App\Http\Controllers;

use App\Mail\ContactDietitianMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactDietitianController extends Controller
{
    public function store()
    {
        request()->validate([
            'name'    => ['required'],
            'email'   => ['email', 'nullable', 'required_without:phone'],
            'phone'   => ['nullable', 'required_without:email'],
            'message' => ['required'],
        ]);

        Mail::to((object) config('mail.addresses.dietitian'))
            ->queue(new ContactDietitianMessage(
                sender: request('name') ?? '',
                email: request('email') ?? '',
                phone: request('phone') ?? '',
                location: request('location') ?? '',
                message: request('message') ?? '',
            ));
    }
}
