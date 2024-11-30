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
        $category->addOns()->create(request()->only([
            'name',
            'description',
            'price'
        ]));
    }

    public function update(AddOn $addOn)
    {
        $addOn->update(request()->only(['name', 'description', 'price']));
    }

    public function delete(AddOn $addOn)
    {
        $addOn->delete();
    }
}
