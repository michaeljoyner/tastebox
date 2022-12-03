<?php

namespace App\Http\Controllers;

use App\Events\OrderConfirmed;
use App\Http\Requests\PlaceOrderRequest;
use App\MailingList\MailingListMember;
use App\Purchases\Kit;
use App\Purchases\Order;
use App\Purchases\PayFast;
use App\Purchases\ShoppingBasket;
use App\SmsReminderSubscriber;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function store(PlaceOrderRequest $request)
    {
        $basket = ShoppingBasket::for(request()->user());
        $kits = $basket->kits->filter(fn (Kit $kit) => $kit->eligibleForOrder());

        $order = Order::makeNew(
            $request->customerDetails(), $kits, $request->discount()
        );

        if($request->allowsNewsletterSignup()) {
            MailingListMember::subscribe($request->email, $request->fullName());
        }

        if($request->allowsSmsSubscription()) {
            SmsReminderSubscriber::addOrUpdate($request->firstName(), $request->phoneNumber());
        }

        if($order->is_paid) {
            $basket->clear();
            event(new OrderConfirmed($order));
            return ['paid_order' => true, 'order_key' => $order->order_key];
        }

        return PayFast::checkoutForm($order);
    }
}
