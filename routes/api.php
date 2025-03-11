<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Middleware\LogExecutionTime;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', LogExecutionTime::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{id}/assign', [TaskController::class, 'assign']);
    Route::put('/tasks/{id}/complete', [TaskController::class, 'complete']);
    Route::get('/tasks', [TaskController::class, 'index']);
});

Route::middleware(LogExecutionTime::class)->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});