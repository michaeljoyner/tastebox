<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Meals\Ingredient;
use Illuminate\Http\Request;

class IngredientsController extends Controller
{

    public function index()
    {
        return Ingredient::orderBy('description')->get()->map->toArray();
    }

    public function store()
    {
        return Ingredient::addNew(request('description'))->toArray();
    }
}
