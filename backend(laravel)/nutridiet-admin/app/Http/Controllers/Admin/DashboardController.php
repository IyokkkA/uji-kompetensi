<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\NutritionLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('is_admin', false)->count();
        $activeUsers = User::where('is_admin', false)->where('status', 'active')->count();
        $todayCalories = NutritionLog::whereDate('log_date', today())->sum('calories');
        $todayWater = NutritionLog::whereDate('log_date', today())->sum('water_ml');
        $todayActivities = ActivityLog::whereDate('log_date', today())->count();

        $goalStats = User::where('is_admin', false)
            ->select('goal', DB::raw('count(*) as total'))
            ->groupBy('goal')->pluck('total', 'goal');

        // 7 hari terakhir kalori
        $weekly = NutritionLog::select(DB::raw('DATE(log_date) as d'), DB::raw('SUM(calories) as total'))
            ->where('log_date', '>=', now()->subDays(6)->toDateString())
            ->groupBy('d')->orderBy('d')->pluck('total', 'd');

        $labels = [];
        $values = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i)->toDateString();
            $labels[] = now()->subDays($i)->format('d M');
            $values[] = (int) ($weekly[$d] ?? 0);
        }

        $recentUsers = User::where('is_admin', false)->latest()->take(6)->get();
        $recentNutrition = NutritionLog::with('user')->latest()->take(8)->get();
        $recentActivities = ActivityLog::with('user')->latest()->take(8)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'activeUsers', 'todayCalories', 'todayWater',
            'todayActivities', 'goalStats', 'labels', 'values',
            'recentUsers', 'recentNutrition', 'recentActivities'
        ));
    }
}
