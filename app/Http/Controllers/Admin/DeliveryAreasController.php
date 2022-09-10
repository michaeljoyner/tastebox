<?php

namespace App\Http\Controllers\Admin;

use App\DeliveryArea;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeliveryAreasController extends Controller
{
    public function index()
    {
        return DeliveryArea::activeAreas();
    }
}
