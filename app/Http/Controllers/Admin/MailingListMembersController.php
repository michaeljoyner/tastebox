<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\MailingList\MailingListMember;
use Illuminate\Http\Request;

class MailingListMembersController extends Controller
{
    public function index()
    {
        return MailingListMember::all();
    }
}
