<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactMessageRequest;
use App\Mail\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    public function store(ContactMessageRequest $request)
    {
        $admins = collect(config('mail.addresses.admins'));

        $admins->each(
            fn ($admin) => Mail::to($admin['email'])->queue(new ContactMessage($request->messageDetails())));

    }
}
