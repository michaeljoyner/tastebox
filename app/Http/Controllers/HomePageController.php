<?php

namespace App\Http\Controllers;

use App\Orders\Menu;
use Dymantic\InstagramFeed\Profile;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function show()
    {
        $menus = Menu::available()->with('meals')->orderBy('current_from')->get();
        $current = $menus->first(fn (Menu $menu) => $menu->isCurrent());
        $instagrams = Profile::where('username', 'tastebox')->first()->feed();


        if(!$current) {
            $current = $menus->shift();
        }
        return view('front.home.page', [
            'current' => optional($current)->presentForPublic(),
            'menus' => $menus->reject(fn (Menu $menu) => $menu->is($current))->map->presentForPublic(),
            'instagrams' => $instagrams->take(8),
        ]);
    }
}
