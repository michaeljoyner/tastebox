<?php

namespace App\Http\Controllers\Admin;

use App\AddOns\AddOn;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImageUploadRequest;
use Illuminate\Http\Request;

class AddOnImageController extends Controller
{

    public function store(ImageUploadRequest $request, AddOn $addOn)
    {
        $image = $addOn->setImage($request->image());

        return ['src' => $image->getUrl("web")];
    }

    public function delete(AddOn $addOn)
    {
        $addOn->clearImage();
    }
}
