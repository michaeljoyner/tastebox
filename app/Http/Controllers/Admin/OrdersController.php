<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminOrderResource;
use App\Http\Resources\AdminOrderResourceCollection;
use App\Purchases\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::with('orderedKits')
            ->latest()
            ->paginate(25);

        return  new AdminOrderResourceCollection($orders);
    }

    public function show(Order $order)
    {
        return new AdminOrderResource($order);
    }
}
