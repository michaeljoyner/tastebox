<?php

namespace App\Http\Controllers;

use App\Purchases\Order;
use Illuminate\Http\Request;

class ThankYouController extends Controller
{
    public function show(Order $order)
    {
        $order->load('orderedKits');

        return view('front.thank-you.page', ['order' => $order]);
    }
}
