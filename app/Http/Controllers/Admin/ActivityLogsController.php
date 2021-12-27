<?php

namespace App\Http\Controllers\Admin;

use App\ActivityLog;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityLogResource;
use Illuminate\Http\Request;

class ActivityLogsController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::query()
            ->latest()
            ->limit(40)
            ->get();

        return ActivityLogResource::collection($logs);
    }
}
