<?php

namespace App\Http\Controllers;

use App\Events\OrderConfirmed;
use App\Purchases\ITNValidator;
use App\Purchases\Order;
use App\Purchases\PayFast;
use App\Purchases\PayfastITN;
use App\Purchases\ShoppingBasket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentsController extends Controller
{
    public function store(Order $order)
    {
        Log::info('payments controller');
        $itn = new PayfastITN(request()->all());

        if ($itn->isTrusted($order) && PayFast::recognizesIP($this->getIp()) && !$order->isPaid())
        {
            $order->acceptPayment($itn);

            event(new OrderConfirmed($order));
        }

        return response('ok');
    }

    private function getIp()
    {
        if(request()->ip() === '127.0.0.1') {
            return request()->header('x-forwarded-for', '127.0.0.1');
        }

        return request()->ip();
    }
}
