<?php

namespace App\Http\Controllers\Admin;

use App\AddOns\AddOn;
use App\AddOns\AddOnCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddOnsController extends Controller
{
    public function store(AddOnCategory $category)
    {
        $category->addOns()->create(['name' => request('name')]);
    }

    public function update(AddOn $addOn)
    {
        $addOn->update(request()->only(['name', 'description']));
    }

    public function delete(AddOn $addOn)
    {
        $addOn->delete();
    }
}
