<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Courses;
use App\Models\Sets;
use Illuminate\Http\Request;

class SetController extends Controller
{
    // C1: Add Set (admin only)
    public function store(Request $request, $course_slug)
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
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid field(s) in request',
                'errors'  => $validator->errors(),
            ], 400);
        }

        // Auto increment order
        $lastOrder = Sets::where('course_id', $course->id)->max('order') ?? 0;

        $set = Sets::create([
            'name'      => $request->name,
            'course_id' => $course->id,
            'order'     => $lastOrder + 1,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Set successfully added',
            'data'    => $set,
        ], 201);
    }

    // C2: Delete Set (admin only)
    public function destroy(Request $request, $course_slug, $set_id)
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

        $set = Sets::where('id', $set_id)->where('course_id', $course->id)->first();

        if (!$set) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'Resource not found',
            ], 404);
        }

        $set->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Set successfully deleted',
        ], 200);
    }
}