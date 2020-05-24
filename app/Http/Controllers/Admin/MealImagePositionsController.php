<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Meals\Meal;
use Illuminate\Http\Request;

class MealImagePositionsController extends Controller
{
    public function update(Meal $meal)
    {
        request()->validate([
            'image_ids' => ['required', 'array'],
            'image_ids.*' => ['exists:media,id']
        ]);

        $meal->setGalleryPositions(request('image_ids'));
    }
}
