<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdjustmentResource;
use App\Purchases\Adjustment;
use Illuminate\Http\Request;

class UnresolvedAdjustmentsController extends Controller
{
    public function index()
    {
        $unresolved = Adjustment::with('creator', 'resolver')
                                ->where('status', Adjustment::STATUS_UNRESOLVED)
                                ->latest()->get();

        return AdjustmentResource::collection($unresolved);
    }
}
