<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Meals\Classification;
use Illuminate\Http\Request;

class ClassificationsController extends Controller
{
    public function index()
    {
        return Classification::all()->map->toArray();
    }
}
