<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrackerController extends Controller
{
    public function nutritionIndex(Request $request)
    {
        $logs = $request->user()->nutritionLogs()
            ->orderByDesc('log_date')->orderByDesc('id')
            ->limit(50)->get();

        return response()->json($logs);
    }

    public function nutritionStore(Request $request)
    {
        $data = $request->validate([
            'log_date' => 'nullable|date',
            'meal_type' => 'nullable|string|max:30',
            'food_name' => 'required|string|max:255',
            'calories' => 'nullable|integer|min:0',
            'protein_g' => 'nullable|numeric|min:0',
            'carbs_g' => 'nullable|numeric|min:0',
            'fat_g' => 'nullable|numeric|min:0',
            'water_ml' => 'nullable|integer|min:0',
        ]);

        $log = $request->user()->nutritionLogs()->create([
            ...$data,
            'log_date' => $data['log_date'] ?? now()->toDateString(),
        ]);

        return response()->json($log, 201);
    }

    public function activityIndex(Request $request)
    {
        $logs = $request->user()->activityLogs()
            ->orderByDesc('log_date')->orderByDesc('id')
            ->limit(50)->get();

        return response()->json($logs);
    }

    public function activityStore(Request $request)
    {
        $data = $request->validate([
            'log_date' => 'nullable|date',
            'activity_type' => 'required|string|max:50',
            'duration_min' => 'nullable|integer|min:1',
            'calories_burned' => 'nullable|integer|min:0',
            'steps' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $log = $request->user()->activityLogs()->create([
            ...$data,
            'log_date' => $data['log_date'] ?? now()->toDateString(),
        ]);

        return response()->json($log, 201);
    }
}
