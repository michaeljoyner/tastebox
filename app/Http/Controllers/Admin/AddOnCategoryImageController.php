<?php

namespace App\Http\Controllers\Admin;

use App\AddOns\AddOnCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImageUploadRequest;
use Illuminate\Http\Request;

class AddOnCategoryImageController extends Controller
{
    public function store(ImageUploadRequest $request, AddOnCategory $category)
    {
        $image = $category->setImage($request->image());

        return ["src" => $image->getUrl("web")];
    }

    public function delete(AddOnCategory $category)
    {
        $category->clearImage();
    }
}
