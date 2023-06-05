<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminMemberCollection;
use App\Http\Resources\AdminMemberResource;
use App\Memberships\MemberPresenter;
use App\User;
use Illuminate\Contracts\Database\Query\Builder;
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
        $term = request("q", false);
        $page = User::query()
                    ->members()
                    ->with('profile', 'orders')
                    ->when(
                        $term,
                        fn(Builder $query) => $query->whereHas('profile',
                            fn(Builder $q) => $q->where('first_name', 'LIKE', "%{$term}%")
                                                ->orWhere('last_name', 'LIKE', "%{$term}%")
                                                ->orWhere('email', 'LIKE', "%{$term}%")
                        ))
                                                    ->orderBy('name')
                                                    ->paginate(30);

        return AdminMemberResource::collection($page);

    }
}
