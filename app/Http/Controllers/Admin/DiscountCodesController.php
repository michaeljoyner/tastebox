<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountCodeRequest;
use App\Purchases\DiscountCode;
use App\Purchases\MemberDiscount;
use Illuminate\Http\Request;

class DiscountCodesController extends Controller
{

    public function index()
    {
        $public = DiscountCode::latest()->get()->map->toArray();
        $members = MemberDiscount::tagged()
                                 ->latest()
                                 ->get()
                                 ->groupBy('discount_tag')
                                 ->map(fn ($group) => $group->first()->toArray());

        return collect([...$members->values()->all(), ...$public->values()->all()])
            ->sort(fn ($a, $b) => $b['timestamp'] - $a['timestamp'])
            ->values()->all();
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
