<?php

namespace App\Http\Controllers;

use App\Purchases\ShoppingBasket;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function show()
    {
        $basket = ShoppingBasket::for(request()->user());

        return view('front.checkout.page', [
            'basket' => $basket->presentForReview()
        ]);
    }
}
