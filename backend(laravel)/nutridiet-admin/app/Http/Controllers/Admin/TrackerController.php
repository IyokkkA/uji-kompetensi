<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\NutritionLog;
use Illuminate\Http\Request;

class TrackerController extends Controller
{
    public function nutrition(Request $request)
    {
        $logs = NutritionLog::with('user')->latest('log_date')->latest()
            ->paginate(12)->withQueryString();

        return view('admin.nutrition', compact('logs'));
    }

    public function activities(Request $request)
    {
        $logs = ActivityLog::with('user')->latest('log_date')->latest()
            ->paginate(12)->withQueryString();

        return view('admin.activities', compact('logs'));
    }
}
