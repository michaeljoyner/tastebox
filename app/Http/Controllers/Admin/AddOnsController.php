<?php

namespace App\Http\Controllers\Admin;

use App\AddOns\AddOn;
use App\AddOns\AddOnCategory;
use App\Http\Controllers\Controller;
use App\Http\Resources\AddOnResource;
use Illuminate\Http\Request;

class AddOnsController extends Controller
{

    public function show(AddOn $addOn)
    {
        $addOn->load('category');
        return AddOnResource::make($addOn);
    }

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
