<?php

namespace App\Mail;

use App\Purchases\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AwaitingPaymentConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    public function __construct(public Order $order)
    {}


    public function build()
    {
        return $this
            ->from('orders@tastebox.co.za')
            ->subject('TasteBox has your order')
            ->markdown('email.customers.awaiting-confirmation', [
                'customer_name' => $this->order->customerFullname(),
            ]);
    }
}
