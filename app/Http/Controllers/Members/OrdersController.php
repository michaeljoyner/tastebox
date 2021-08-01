<?php

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use App\Purchases\OrderPresenter;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = request()
            ->user()
            ->orders()
            ->with('orderedKits', 'payment')
            ->latest()
            ->get()
            ->map(fn($o) => OrderPresenter::forMember($o));

        return view('members.orders.index', ['orders' => $orders]);
    }
}
