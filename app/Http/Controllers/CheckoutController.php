<?php

namespace App\Http\Controllers;

use App\Meals\Meal;
use App\Purchases\ShoppingBasket;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function show()
    {
        $basket = ShoppingBasket::for(request()->user());

        if($basket->price() < 3 * Meal::SERVING_PRICE) {
            return view('front.checkout.nothing-to-checkout', [
                'basket' => $basket->presentForReview()
            ]);
        }

        return view('front.checkout.page', [
            'basket' => $basket->presentForReview()
        ]);
    }
}
