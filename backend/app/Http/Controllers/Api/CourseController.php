<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Courses;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // B1: Add Course (admin only)
    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'status'  => 'insufficient_permissions',
                'message' => 'Access forbidden',
            ], 403);
        }

        $validator = validator($request->all(), [
            'name'        => 'required',
            'description' => 'nullable',
            'slug'        => 'required|unique:courses,slug',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid field(s) in request',
                'errors'  => $validator->errors(),
            ], 400);
        }

        $course = Courses::create([
            'name'         => $request->name,
            'description'  => $request->description,
            'slug'         => $request->slug,
            'is_published' => 0,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Course successfully added',
            'data'    => $course,
        ], 201);
    }

    // B2: Edit Course (admin only)
    public function update(Request $request, $course_slug)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'status'  => 'insufficient_permissions',
                'message' => 'Access forbidden',
            ], 403);
        }

        $course = Courses::where('slug', $course_slug)->first();

        if (!$course) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'Resource not found',
            ], 404);
        }

        $validator = validator($request->all(), [
            'name'         => 'required',
            'description'  => 'nullable',
            'is_published' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid field(s) in request',
                'errors'  => $validator->errors(),
            ], 400);
        }

        $course->update([
            'name'         => $request->name,
            'description'  => $request->description,
            'is_published' => $request->is_published ?? $course->is_published,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Course successfully updated',
            'data'    => $course,
        ], 200);
    }

    // B3: Delete Course (admin only)
    public function destroy(Request $request, $course_slug)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'status'  => 'insufficient_permissions',
                'message' => 'Access forbidden',
            ], 403);
        }

        $course = Courses::where('slug', $course_slug)->first();

        if (!$course) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'Resource not found',
            ], 404);
        }

        $course->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Course successfully deleted',
        ], 200);
    }

    // B4: Get All Published Courses
    public function index(Request $request)
    {
        $courses = Courses::where('is_published', 1)->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Courses retrieved successfully',
            'data'    => [
                'courses' => $courses,
            ],
        ], 200);
    }

    // B5: Get Course Details
    public function show(Request $request, $course_slug)
    {
        $course = Courses::where('slug', $course_slug)
            ->with([
                'sets' => function ($query) {
                    $query->orderBy('order')->with([
                        'lessons' => function ($query) {
                            $query->orderBy('order')->with([
                                'contents' => function ($query) {
                                    $query->orderBy('order')->with('options');
                                }
                            ]);
                        }
                    ]);
                }
            ])
            ->first();

        if (!$course) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'Resource not found',
            ], 404);
        }

        // Sembunyikan options untuk tipe 'learn'
        $course->sets->each(function ($set) {
            $set->lessons->each(function ($lesson) {
                $lesson->contents->each(function ($content) {
                    if ($content->type === 'learn') {
                        $content->makeHidden('options');
                    }
                });
            });
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Course details retrieved successfully',
            'data'    => $course,
        ], 200);
    }
}