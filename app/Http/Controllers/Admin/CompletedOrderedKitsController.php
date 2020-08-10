<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Purchases\OrderedKit;
use Illuminate\Http\Request;

class CompletedOrderedKitsController extends Controller
{
    public function store()
    {
        request()->validate([
            'ordered_kit_ids'   => ['array'],
            'ordered_kit_ids.*' => ['exists:ordered_kits,id'],
        ]);

        OrderedKit::find(request('ordered_kit_ids'))
                  ->each(fn(OrderedKit $kit) => $kit->packedAndDelivered());
    }
}
