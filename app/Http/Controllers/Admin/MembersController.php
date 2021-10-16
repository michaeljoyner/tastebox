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

        return new AdminMemberResource($member);
    }

    public function index()
    {
        $page = User::query()
                    ->members()
                    ->with('profile', 'orders.orderedKits')
                    ->orderBy('name')
                    ->paginate(20);

        return new AdminMemberCollection($page);

//        return [
//            'page'        => $page->currentPage(),
//            'items'       => collect($page->items())->map(fn($user) => MemberPresenter::forAdmin($user)),
//            'total_pages' => $page->lastPage(),
//        ];
    }
}
