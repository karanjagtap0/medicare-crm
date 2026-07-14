<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', [AuthController::class, 'profile']);

    Route::post('/logout', [AuthController::class, 'logout']);

});

Route::middleware(['auth:sanctum', 'role:Super Admin'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index']);

    // Users
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Categories
    Route::get('/categories', [\App\Http\Controllers\Master\CategoryController::class, 'index']);
    Route::post('/categories', [\App\Http\Controllers\Master\CategoryController::class, 'store']);
    Route::get('/categories/{id}', [\App\Http\Controllers\Master\CategoryController::class, 'show']);
    Route::put('/categories/{id}', [\App\Http\Controllers\Master\CategoryController::class, 'update']);
    Route::patch('/categories/{id}/status', [\App\Http\Controllers\Master\CategoryController::class, 'updateStatus']);
    Route::delete('/categories/{id}', [\App\Http\Controllers\Master\CategoryController::class, 'destroy']);

    // Brands
    Route::get('/brands', [\App\Http\Controllers\Master\BrandController::class, 'index']);
    Route::post('/brands', [\App\Http\Controllers\Master\BrandController::class, 'store']);
    Route::get('/brands/{id}', [\App\Http\Controllers\Master\BrandController::class, 'show']);
    Route::put('/brands/{id}', [\App\Http\Controllers\Master\BrandController::class, 'update']);
    Route::patch('/brands/{id}/status', [\App\Http\Controllers\Master\BrandController::class, 'updateStatus']);
    Route::delete('/brands/{id}', [\App\Http\Controllers\Master\BrandController::class, 'destroy']);
});
