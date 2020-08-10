<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Purchases\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::where('created_at', '>', Carbon::today()->subMonths(3))
            ->latest()->get()->map->summarizeForAdmin();

        return $orders->groupBy(fn ($order) => $order['batch'])->toArray();
    }

    public function show(Order $order)
    {
        return $order->presentForAdmin();
    }
}
