<?php

namespace App\Http\Controllers;

use App\Purchases\BasketPresenter;
use App\Purchases\ShoppingBasket;
use Illuminate\Http\Request;

class MealKitsController extends Controller
{

    public function store()
    {
        request()->validate([
            'menu_id' => ['required', 'exists:menus,id']
        ]);

        $basket = ShoppingBasket::for(request()->user());
        $kit = $basket->addKit(request('menu_id'));

        return (new BasketPresenter($basket))->presentKit($kit);
    }

    public function destroy($kit_id)
    {
        $basket = ShoppingBasket::for(request()->user());
        $basket->discardKit($kit_id);

        return $basket->presentForReview();
    }
}
