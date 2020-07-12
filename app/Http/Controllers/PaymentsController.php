<?php

namespace App\Http\Controllers;

use App\Purchases\ITNValidator;
use App\Purchases\Order;
use App\Purchases\PayFast;
use App\Purchases\PayfastITN;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentsController extends Controller
{
    public function store(Order $order)
    {
        $itn = new PayfastITN(request()->all());

        if ($itn->isTrusted($order) && PayFast::recognizesIP($this->getIp()))
        {
            $order->acceptPayment($itn);
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
