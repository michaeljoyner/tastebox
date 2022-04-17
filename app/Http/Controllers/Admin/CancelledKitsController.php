<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Purchases\OrderedKit;
use Illuminate\Http\Request;

class CancelledKitsController extends Controller
{
    public function store()
    {
        $kit = OrderedKit::findOrFail(request('kit_id'));

        $kit->cancel(request('reason'), request()->user());
    }
}
