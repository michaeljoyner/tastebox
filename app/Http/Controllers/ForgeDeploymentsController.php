<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class ForgeDeploymentsController extends Controller
{
    public function store()
    {
        Log::info(request()->all());
    }
}
