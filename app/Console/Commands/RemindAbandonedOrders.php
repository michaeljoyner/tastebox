<?php

namespace App\Console\Commands;

use App\Mail\RestoreAbandonedOrderMail;
use App\Purchases\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class RemindAbandonedOrders extends Command
{

    protected $signature = 'orders:remind-abandoned';

    protected $description = 'Send reminder to recently abandoned orders';

    public function handle()
    {
        $recently_abandoned = Order::recentlyAbandoned()
                                   ->get()
                                   ->reject(
                                       fn(Order $order) => Order::hasRecentlyPlacedBy($order->email)
                                   );

        $recently_abandoned->each(fn(Order $order) => Mail::to($order->email)->queue(new RestoreAbandonedOrderMail($order)));

        return 0;
    }
}
