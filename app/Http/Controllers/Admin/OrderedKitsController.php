<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderedKitRequest;
use App\Http\Resources\AdminOrderedKitsResource;
use App\Http\Resources\AdminOrderedKitsResourceCollection;
use App\Purchases\Adjustment;
use App\Purchases\OrderedKit;
use Illuminate\Http\Request;

class OrderedKitsController extends Controller
{

    public function show(OrderedKit $kit)
    {
        return new AdminOrderedKitsResource($kit);
    }

    public function index()
    {
        $kits = OrderedKit::latest('delivery_date')
            ->paginate(40);

        return new AdminOrderedKitsResourceCollection($kits);
    }

    public function update(UpdateOrderedKitRequest $request, OrderedKit $kit)
    {
        $original_value = $kit->value();

        $kit->setMeals($request->meals());


        Adjustment::new(
            $original_value,
            $kit->fresh()->value(),
            $kit->order,
            $request->reason ?? '',
            $request->user(),
        );
    }
}
