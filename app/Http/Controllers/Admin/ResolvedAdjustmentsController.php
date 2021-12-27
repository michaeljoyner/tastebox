<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Purchases\Adjustment;
use Illuminate\Http\Request;

class ResolvedAdjustmentsController extends Controller
{
    public function store()
    {
        $adjustment = Adjustment::findOrFail(request('adjustment_id'));

        $adjustment->resolve(request()->user(), request('note'));
    }
}
