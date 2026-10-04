<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * POST /api/register
     * Body: name, email, password, password_confirmation, goal [, phone]
     * Sesuai RegisterActivity.kt
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'goal' => ['nullable', Rule::in(['weight_loss', 'maintenance', 'healthy_bulk'])],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'goal' => $data['goal'] ?? 'weight_loss',
        ]);

        $token = $user->createToken('android')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'token' => $token,
            'name' => $user->name,
            'email' => $user->email,
            'goal' => $user->goal,
            'user' => $user,
        ], 201);
    }

    /**
     * POST /api/login
     * Body: email ATAU phone + password. Sesuai LoginActivity.kt
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'password' => 'required|string',
        ]);

        if (! $request->email && ! $request->phone) {
            return response()->json(['message' => 'Email atau nomor HP wajib diisi.'], 422);
        }

        $user = $request->email
            ? User::where('email', $request->email)->first()
            : User::where('phone', $request->phone)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email / nomor HP atau password salah.'], 401);
        }

        if ($user->status !== 'active') {
            return response()->json(['message' => 'Akun dinonaktifkan. Hubungi admin.'], 403);
        }

        $token = $user->createToken('android')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'name' => $user->name,
            'email' => $user->email,
            'goal' => $user->goal,
            'user' => $user,
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(['message' => 'Logout berhasil']);
    }
}
