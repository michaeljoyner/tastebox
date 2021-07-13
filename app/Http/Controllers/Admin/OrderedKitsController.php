<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Purchases\OrderedKit;
use App\Purchases\OrderedKitPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class OrderedKitsController extends Controller
{
    public function index()
    {
        $kits = OrderedKit::with('order')
            ->where('delivery_date', '>=', now())
            ->whereHas('order', fn (Builder $query) => $query->where('is_paid', true))
            ->get()
            ->map(fn (OrderedKit $kit) => OrderedKitPresenter::forAdmin($kit));

        return $kits;
    }
}
