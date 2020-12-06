<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountCodeRequest;
use App\Purchases\DiscountCode;
use Illuminate\Http\Request;

class DiscountCodesController extends Controller
{

    public function index()
    {
        return DiscountCode::latest()->get()->map->toArray();
    }

    public function store(DiscountCodeRequest $request)
    {
        return DiscountCode::new($request->codeInfo());
    }

    public function update(DiscountCodeRequest $request, DiscountCode $code)
    {
        $code->update($request->codeInfo()->toArray());
    }

    public function delete(DiscountCode $code)
    {
        $code->delete();
    }
}
