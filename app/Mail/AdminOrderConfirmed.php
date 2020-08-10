<?php

namespace App\Mail;

use App\Purchases\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminOrderConfirmed extends Mailable
{
    use Queueable, SerializesModels;


    public string $customer_name;
    public string $customer_email;
    public string $customer_phone;
    public $boxes;
    public $amount_paid;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Customer $customer, $boxes, $amount_paid)
    {
        //
        $this->customer_name = $customer->name;
        $this->customer_email = $customer->email;
        $this->customer_phone = $customer->phone;
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
            ->from('the-boss@tastebox.co.za')
            ->subject('Kaching! We have a new order!')
            ->markdown('email.admin.order-confirmed');
    }
}
