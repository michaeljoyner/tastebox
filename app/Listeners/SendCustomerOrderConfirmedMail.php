<?php

namespace App\Listeners;

use App\Events\OrderConfirmed;
use App\Mail\OrderConfirmed as OrderConfirmedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendCustomerOrderConfirmedMail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param OrderConfirmed $event
     * @return void
     */
    public function handle(OrderConfirmed $event)
    {
        $event->order->load('orderedKits', 'payment');
        $customer = $event->order->customer();

        Mail::to($customer)
            ->queue(new OrderConfirmedMail(
                $customer->name,
                $event->order->orderedKits->map->summarize(),
                $event->order->payment->amount_gross
            ));
    }
}
