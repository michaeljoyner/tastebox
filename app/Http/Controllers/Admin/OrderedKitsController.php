<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Purchases\OrderedKit;
use App\Purchases\OrderedKitPresenter;
use Illuminate\Http\Request;

class OrderedKitsController extends Controller
{
    public function index()
    {
        $kits = OrderedKit::with('order')
            ->where('delivery_date', '>=', now())
            ->get()
            ->map(fn (OrderedKit $kit) => OrderedKitPresenter::forAdmin($kit));

        return $kits;
    }
}
