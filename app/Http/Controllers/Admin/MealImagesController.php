<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Meals\Meal;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MealImagesController extends Controller
{
    public function store(Meal $meal)
    {
        request()->validate(['image' => ['required', 'image']]);

        $image = $meal->addImage(request('image'));

        return ['src' => $image->getUrl('web'), 'id' => $image->id];
    }

    public function destroy(Meal $meal, Media $media)
    {
        if(intval($media->model_id) !== $meal->id) {
            abort(422);
        }

        $media->delete();
    }
}
