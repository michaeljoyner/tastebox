<?php

namespace App\Http\Controllers;

use App\Orders\Menu;
use App\Purchases\ShoppingBasket;
use Illuminate\Http\Request;

class KitBuilderController extends Controller
{
    public function show()
    {
        $menus = Menu::available()->with('meals')->orderBy('current_from')->get();
        $basket = ShoppingBasket::for(auth()->user());
        return view('front.kit-builder.page', [
            'menus' => $menus->map->presentForPublic(),
            'basket' => $basket->presentForReview(),
            'kit' => request('kit'),
        ]);
    }
}
