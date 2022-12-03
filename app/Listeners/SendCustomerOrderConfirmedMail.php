<?php

namespace App\Listeners;

use App\Events\OrderConfirmed;
use App\Mail\OrderConfirmed as OrderConfirmedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendCustomerOrderConfirmedMail
{

    public function __construct()
    {
        //
    }


    public function handle(OrderConfirmed $event)
    {
        $event->order->load('orderedKits', 'payment');
        $customer = $event->order->customer();

        Mail::to($customer)
            ->queue(new OrderConfirmedMail(
                $customer->name,
                $event->order->orderedKits->map->summarize(),
                $event->order->payment?->amount_gross ?? 0
            ));

        $event->order->markNotificationSent();
    }
}
