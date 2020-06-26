<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Orders\Menu;
use Illuminate\Http\Request;

class OrderableMenusController extends Controller
{
    public function create()
    {
        $menu = Menu::findOrFail(request('menu_id'));

        $menu->openForOrders();
    }

    public function destroy(Menu $menu)
    {
        $menu->closedForOrders();
    }
}
