<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Orders\Menu;
use Illuminate\Http\Request;

class UpcomingMenusController extends Controller
{
    public function index()
    {
        return Menu::with('meals.ingredients')->upcoming()->get()->map->toArray();
    }
}
