<?php

namespace App\Http\Controllers;

use App\Orders\Menu;
use Illuminate\Http\Request;

class KitBuilderController extends Controller
{
    public function show()
    {
        $menus = Menu::available()->with('meals')->orderBy('current_from')->get();
        return view('front.kit-builder.page', ['menus' => $menus->map->presentForPublic()]);
    }
}
