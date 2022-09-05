<?php

namespace App\Http\Controllers;

use App\DeliveryAddress;
use App\Http\Requests\DeliveryAddressRequest;
use App\Purchases\ShoppingBasket;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class KitDeliveryAddressController extends Controller
{
    public function update(DeliveryAddressRequest $request, string $kit_id)
    {

        $cart = ShoppingBasket::for($request->user());

        $kit = $cart->getKitOrFail($kit_id);

        if ($request->forCustomAddress()) {
            $request->forAllUnsetKits() ? $cart->setAddressForUnsetKits($request->deliveryAddress()) : $kit->setDeliveryAddress($request->deliveryAddress());

            return $cart->presentForReview();
        }

        if ($request->toDeliverWithKit()) {
            $kit->deliverWith($request->deliveryKit());

            return $cart->presentForReview();
        }


    }
}
