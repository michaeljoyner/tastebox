<?php

namespace App\Mail;

use App\Purchases\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class OrderConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $customer_name;
    public $boxes;
    public $amount_paid;

    public function __construct($customer_name, $boxes, $amount_paid)
    {
        $this->customer_name = $customer_name;
        $this->boxes = $boxes;
        $this->amount_paid = $amount_paid;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('orders@tastebox.co.za')
            ->subject('Your TasteBox order confirmed')
            ->markdown('email.customers.order-confirmed');
    }
}
