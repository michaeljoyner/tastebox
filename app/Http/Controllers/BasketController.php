<?php

namespace App\Http\Controllers;

use App\Purchases\ShoppingBasket;
use Illuminate\Http\Request;

class BasketController extends Controller
{
    public function show()
    {
        $basket = ShoppingBasket::for(request()->user());

        return view('front.basket.page', [
            'basket' => $basket->presentForReview(),
        ]);
    }
}
