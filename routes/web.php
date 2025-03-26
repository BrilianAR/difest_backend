<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return response()->json(['message' => 'Halaman Admin']);
    });
});

Route::middleware(['auth:api', 'role:juri'])->group(function () {
    Route::get('/juri/dashboard', function () {
        return response()->json(['message' => 'Halaman Juri']);
    });
});