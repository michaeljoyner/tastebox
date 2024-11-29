<?php

namespace App\Http\Controllers\Admin;

use App\AddOns\AddOnCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddOnCategoriesController extends Controller
{
    public function store()
    {
        AddOnCategory::create(['name' => request('name')]);
    }

    public function update(AddOnCategory $category)
    {
        $category->update(request()->only(['name', 'description']));
    }

    public function delete(AddOnCategory $category)
    {
        $category->delete();
    }
}
