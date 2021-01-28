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

        $file = $batch->createShoppingListPdf();

        return Storage::disk('admin_stuff')->download($file);
    }
}
