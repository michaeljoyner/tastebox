<?php

namespace App\Http\Controllers;

use App\Orders\Menu;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function show()
    {
        $menus = Menu::available()->with('meals')->orderBy('current_from')->get();
        $current = $menus->first(fn (Menu $menu) => $menu->isCurrent());

        if(!$current) {
            $current = $menus->shift();
        }
        return view('front.home.page', [
            'current' => optional($current)->presentForPublic(),
            'menus' => $menus->reject(fn (Menu $menu) => $menu->is($current))->map->presentForPublic(),
        ]);
    }
}
