<?php

namespace App\Http\Controllers;

use App\Purchases\ShoppingBasket;
use Illuminate\Http\Request;

class MealKitsController extends Controller
{
    public function show($kit_id)
    {
        $basket = ShoppingBasket::for(request()->user());
        $menu = $basket->getMenuForKit($kit_id);
        return view('front.kits.page', [
            'menu' => $menu->presentForPublic(),
            'kit' => $basket->getKit($kit_id)->toArray(),
        ]);
    }

    public function store()
    {
        request()->validate([
            'menu_id' => ['required', 'exists:menus,id']
        ]);

        $basket = ShoppingBasket::for(request()->user());
        $kit = $basket->addKit(request('menu_id'));

        return redirect("my-kits/{$kit->id}");
    }

    public function destroy($kit_id)
    {
        $basket = ShoppingBasket::for(request()->user());
        $basket->discardKit($kit_id);
    }
}
