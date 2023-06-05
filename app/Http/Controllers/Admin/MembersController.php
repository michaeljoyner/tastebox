<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminMemberCollection;
use App\Http\Resources\AdminMemberResource;
use App\Memberships\MemberPresenter;
use App\User;
use Illuminate\Http\Request;

class MembersController extends Controller
{

    public function show(User $member)
    {
        $member->load('profile', 'orders.orderedKits');

        return AdminMemberResource::make($member);
    }

    public function index()
    {
        $page = User::query()
                    ->members()
                    ->with('profile', 'orders')
                    ->orderBy('name')
                    ->paginate(30);

        return AdminMemberResource::collection($page);

    }
}
