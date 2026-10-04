<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\NutritionLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $goal = $request->get('goal');
        $status = $request->get('status');

        $users = User::where('is_admin', false)
            ->when($q, fn ($query) => $query->where(fn ($w) => $w
                ->where('name', 'like', "%$q%")
                ->orWhere('email', 'like', "%$q%")
                ->orWhere('phone', 'like', "%$q%")))
            ->when($goal, fn ($query) => $query->where('goal', $goal))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->withCount(['nutritionLogs', 'activityLogs'])
            ->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users', 'q', 'goal', 'status'));
    }

    public function show(User $user)
    {
        $user->loadCount(['nutritionLogs', 'activityLogs']);
        $nutrition = NutritionLog::where('user_id', $user->id)->latest('log_date')->take(15)->get();
        $activities = ActivityLog::where('user_id', $user->id)->latest('log_date')->take(15)->get();
        $totalCalories = NutritionLog::where('user_id', $user->id)->sum('calories');
        $totalWater = NutritionLog::where('user_id', $user->id)->sum('water_ml');
        $totalBurned = ActivityLog::where('user_id', $user->id)->sum('calories_burned');

        return view('admin.users.show', compact('user', 'nutrition', 'activities', 'totalCalories', 'totalWater', 'totalBurned'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30|unique:users,phone',
            'password' => 'required|string|min:8',
            'goal' => ['required', Rule::in(['weight_loss', 'maintenance', 'healthy_bulk'])],
            'height_cm' => 'nullable|integer|min:100|max:250',
            'weight_kg' => 'nullable|numeric|min:20|max:300',
            'target_calories' => 'nullable|integer|min:500|max:6000',
            'water_target' => 'nullable|integer|min:500|max:6000',
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect('/admin/users')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'goal' => ['required', Rule::in(['weight_loss', 'maintenance', 'healthy_bulk'])],
            'height_cm' => 'nullable|integer|min:100|max:250',
            'weight_kg' => 'nullable|numeric|min:20|max:300',
            'target_calories' => 'nullable|integer|min:500|max:6000',
            'water_target' => 'nullable|integer|min:500|max:6000',
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return redirect("/admin/users/{$user->id}")->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect('/admin/users')->with('success', 'User dihapus.');
    }

    public function toggleStatus(User $user)
    {
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return back()->with('success', "Status {$user->name} → {$user->status}.");
    }
}
