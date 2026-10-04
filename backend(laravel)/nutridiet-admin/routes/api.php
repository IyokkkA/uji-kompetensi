<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TrackerController;
use Illuminate\Support\Facades\Route;

// Dipakai aplikasi Android (lihat ApiConfig + RegisterActivity/LoginActivity)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/nutrition', [TrackerController::class, 'nutritionIndex']);
    Route::post('/nutrition', [TrackerController::class, 'nutritionStore']);
    Route::get('/activities', [TrackerController::class, 'activityIndex']);
    Route::post('/activities', [TrackerController::class, 'activityStore']);
});
