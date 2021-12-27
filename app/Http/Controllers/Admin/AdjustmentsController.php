<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdjustmentResource;
use App\Purchases\Adjustment;
use Illuminate\Http\Request;

class AdjustmentsController extends Controller
{

    public function show(Adjustment $adjustment)
    {
        return new AdjustmentResource($adjustment);
    }

    public function index()
    {
        return AdjustmentResource::collection(Adjustment::latest()->paginate(20));
    }
}
