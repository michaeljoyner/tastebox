<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuFreeRecipesRequest;
use App\Orders\Menu;
use Illuminate\Http\Request;

class FreeRecipesController extends Controller
{
    public function store(MenuFreeRecipesRequest $request, Menu $menu)
    {
        $menu->addFreeRecipes($request->meals());
    }
}
