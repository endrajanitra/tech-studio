<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\SetController;
use App\Http\Controllers\Api\LessonController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Course routes
    Route::post('/courses',                 [CourseController::class, 'store']);
    Route::put('/courses/{course_slug}',    [CourseController::class, 'update']);
    Route::delete('/courses/{course_slug}', [CourseController::class, 'destroy']);
    Route::get('/courses',                  [CourseController::class, 'index']);
    Route::get('/courses/{course_slug}',    [CourseController::class, 'show']);

    // Set routes
    Route::post('/courses/{course_slug}/sets',          [SetController::class, 'store']);
    Route::delete('/courses/{course_slug}/sets/{set_id}', [SetController::class, 'destroy']);

    // Lesson routes
    Route::post('/sets/{set_id}/lessons',               [LessonController::class, 'store']);
    Route::delete('/sets/{set_id}/lessons/{lesson_id}', [LessonController::class, 'destroy']);
});