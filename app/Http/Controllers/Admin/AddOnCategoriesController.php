<?php

namespace App\Http\Controllers\Admin;

use App\AddOns\AddOnCategory;
use App\Http\Controllers\Controller;
use App\Http\Resources\AddOnCategoryResource;
use Illuminate\Http\Request;

class AddOnCategoriesController extends Controller
{

    public function index()
    {
        $categories = AddOnCategory::with('addOns')->get();

        return AddOnCategoryResource::collection($categories);
    }

    public function show(AddOnCategory $category)
    {
        $category->load('addOns');
        return AddOnCategoryResource::make($category);
    }


    public function store()
    {
        request()->validate([
            'name' => ['required']
        ]);

        AddOnCategory::create(request()->only(['name', 'description']));
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
