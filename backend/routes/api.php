<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Course routes
    Route::post('/courses',              [CourseController::class, 'store']);
    Route::put('/courses/{course_slug}', [CourseController::class, 'update']);
    Route::delete('/courses/{course_slug}', [CourseController::class, 'destroy']);
    Route::get('/courses',               [CourseController::class, 'index']);
    Route::get('/courses/{course_slug}', [CourseController::class, 'show']);
});