<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Orders\Menu;
use Illuminate\Http\Request;

class MenuAddOnsController extends Controller
{

    public function store(Menu $menu)
    {
        request()->validate([
            'add_on_ids' => ['present', 'array'],
            'add_on_ids.*' => ['exists:add_ons,id'],
        ]);

        $menu->addOns()->sync(request('add_on_ids'));
    }
}
