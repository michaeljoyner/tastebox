<?php

namespace App\Listeners;

use App\Events\OrderConfirmed;
use App\Mail\AdminOrderConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendAdminOrderConfirmedMail
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
     * @param  OrderConfirmed  $event
     * @return void
     */
    public function handle(OrderConfirmed $event)
    {
        $customer = $event->order->customer();
        $boxes = $event->order->orderedKits->map->summarize();
        $amount_paid = $event->order->payment->amount_gross;
        Mail::to('test@test.test')
            ->queue(new AdminOrderConfirmed($customer, $boxes, $amount_paid));
    }
}
