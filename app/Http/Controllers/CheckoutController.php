<?php

namespace App\Http\Controllers;

use App\Meals\Meal;
use App\Meals\MealPriceTier;
use App\Purchases\ShoppingBasket;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function show()
    {
        $user = request()->user();
        $basket = ShoppingBasket::for($user);

        if($basket->missingDeliveryAddresses()) {
            return redirect("/basket");
        }

        if($basket->price() < 3 * MealPriceTier::BUDGET->price()) {
            return view('front.checkout.nothing-to-checkout', [
                'basket' => $basket->presentForReview()
            ]);
        }

        return view('front.checkout.page', [
            'basket' => $basket->presentForReview(),
            'profile' => $user?->profile->toArray(),
            'discounts' => $user?->discounts->filter(fn ($discount) => $discount->isValid())->map(fn ($discount) => $discount->toArray())->values(),
        ]);
    }
}
