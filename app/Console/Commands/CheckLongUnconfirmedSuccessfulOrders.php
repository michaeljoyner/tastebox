<?php

namespace App\Console\Commands;

use App\Mail\AwaitingPaymentConfirmation;
use App\Purchases\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckLongUnconfirmedSuccessfulOrders extends Command
{

    protected $signature = 'orders:notify-long-pending';


    protected $description = 'Checks and notifies customers whose orders have not been confirmed by payfast for at least three hours';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $still_awaiting = Order::pending()
                               ->unconfirmed()
                               ->where('updated_at', '<=', now()->subHours(3))
                               ->get();

        $still_awaiting->each(
            fn(Order $order) => $order->notifyCustomerAwaitingConfirmation());

        return 0;
    }
}
