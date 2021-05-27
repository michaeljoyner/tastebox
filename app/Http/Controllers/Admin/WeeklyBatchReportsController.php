<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Orders\BatchReport;
use Illuminate\Http\Request;

class WeeklyBatchReportsController extends Controller
{
    public function index()
    {
        return BatchReport::orderBy('week')
                          ->limit(50)
                          ->get()
                          ->map(fn ($batch) => [
                              'week' => $batch->week,
                              'kits' => $batch->total_kits,
                              'meals' => $batch->total_meals,
                              'servings' => $batch->total_servings,
                          ]);
    }
}
