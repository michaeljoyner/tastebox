<?php

namespace App\Mail;

use App\Purchases\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RestoreAbandonedOrderMail extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct(public Order $order)
    {}


    public function build()
    {
        return $this->subject("Continue your order")
        ->markdown('email.customers.recently-abandoned', [
            'restore_link' => $this->restorationUrl(),
            'name' => $this->getCustomerName(),
            'boxes' => $this->order->orderedKits->map->summarize()
        ]);
    }

    private function getCustomerName(): string
    {
        if($this->order->member) {
            return $this->order->member->profile->first_name;
        }

        return $this->order->first_name;
    }

    public function restorationUrl(): string
    {
        if($this->order->member) {
            return url("/me/revived-orders/{$this->order->order_key}");
        }

        return url("/revived-orders/{$this->order->order_key}");
    }
}
