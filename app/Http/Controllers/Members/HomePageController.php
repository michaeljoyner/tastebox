<?php

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use App\Purchases\OrderedKitPresenter;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function show()
    {
        $member = request()->user();
        $profile = $member->profile->toArray();
        $upcoming_kits = $member->upcomingKits()->map(fn ($kit) => OrderedKitPresenter::forMember($kit));
        $has_placed_order = $member->hasPlacedOrderForNextMenu();


        return view('members.home.page', [
            'profile' => $profile,
            'upcoming_kits' => $upcoming_kits,
            'has_next_order' => $has_placed_order,
            'discounts' => $member->discounts->filter->isValid(),
        ]);


    }
}
