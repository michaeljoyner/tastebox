<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Meals\Meal;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Support\MediaStream;
use ZipStream\Option\Archive;

class MenuImagesDownloadController extends Controller
{
    public function show()
    {
        $meals = Meal::find(request('meal_ids'))
                     ->filter(fn(Meal $meal) => $meal->titleMedia() !== null)
                     ->map(fn(Meal $meal) => $meal->titleMedia());

        if($meals->count() === 1) {
            return $meals->first();
        }

        return MediaStream::create('menu-images.zip')
                          ->addMedia($meals)
                          ->useZipOptions(fn(Archive $opt) => $opt->setZeroHeader(true));

    }
}
