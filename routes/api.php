<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ModulesController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected module routes (require auth:sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/modules', [ModulesController::class, 'index']);

    Route::post('/modules/{id}/activate', [ModulesController::class, 'activate']);

    Route::post('/modules/{id}/deactivate', [ModulesController::class, 'deactivate']);
    
    
    Route::get('/url-shortener/info', function () {
        return response()->json(['message' => 'URL Shortener endpoint accessible']);
    })->middleware([\App\Http\Middleware\CheckModuleActive::class . ':1']);
    
    Route::get('/wallet/info', function () {
        return response()->json(['message' => 'Wallet endpoint accessible']);
    })->middleware([\App\Http\Middleware\CheckModuleActive::class . ':2']);
});
