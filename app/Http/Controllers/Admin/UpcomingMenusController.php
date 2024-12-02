<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use App\Orders\Menu;
use Illuminate\Http\Request;

class UpcomingMenusController extends Controller
{
    public function index()
    {
        $menus =  Menu::with('meals.ingredients', 'freeRecipeMeals', 'addOns')->upcoming()->get();

        return MenuResource::collection($menus);
    }
}
