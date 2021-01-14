<?php

namespace App\Http\Controllers\Admin;

use App\DatePresenter;
use App\Http\Controllers\Controller;
use App\Orders\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;

class ShoppingListController extends Controller
{
    public function download(Menu $menu)
    {

        $batch = $menu->getBatch();
        $file_name = sprintf("shopping-lists/shopping_list_batch_%s.pdf", $menu->weekOfYear());

        $html = view('admin.batches.shopping-list', [
            'batch_number' => $batch->week,
            'delivery_date' => DatePresenter::pretty($batch->deliveryDate()),
            'ingredients'  => $batch->ingredientList(),
        ])->render();

        Browsershot::html($html)
                   ->margins(25, 5, 25, 5)
                   ->setNodeBinary(config('browsershot.node_path'))
                   ->setNpmBinary(config('browsershot.npm_path'))
                   ->savePdf(storage_path("app/public/{$file_name}"));

        return Storage::disk('public')->download($file_name);
    }
}
