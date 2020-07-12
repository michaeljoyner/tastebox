<?php

namespace App\Http\Controllers;

use App\Purchases\ShoppingBasket;
use Illuminate\Http\Request;

class BasketSummaryController extends Controller
{
    public function show()
    {
        $basket = ShoppingBasket::for(request()->user());
        return $basket->presentForReview();
    }
}
