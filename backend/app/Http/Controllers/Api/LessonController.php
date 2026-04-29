<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sets;
use App\Models\Lessons;
use App\Models\LessonContents;
use App\Models\Options;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    // D1: Add Lesson (admin only)
    public function store(Request $request, $set_id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'status'  => 'insufficient_permissions',
                'message' => 'Access forbidden',
            ], 403);
        }

        $set = Sets::find($set_id);

        if (!$set) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'Resource not found',
            ], 404);
        }

        $validator = validator($request->all(), [
            'name'                      => 'required',
            'contents'                  => 'required|array|min:1',
            'contents.*.type'           => 'required|in:learn,quiz',
            'contents.*.content'        => 'required',
            'contents.*.options'        => 'required_if:contents.*.type,quiz|array',
            'contents.*.options.*.option_text' => 'required_if:contents.*.type,quiz',
            'contents.*.options.*.is_correct'  => 'required_if:contents.*.type,quiz|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid field(s) in request',
                'errors'  => $validator->errors(),
            ], 400);
        }

        // Auto increment order
        $lastOrder = Lessons::where('set_id', $set->id)->max('order') ?? 0;

        $lesson = Lessons::create([
            'set_id' => $set->id,
            'name'   => $request->name,
            'order'  => $lastOrder + 1,
        ]);

        // Create contents & options
        foreach ($request->contents as $index => $contentData) {
            $content = LessonContents::create([
                'lesson_id' => $lesson->id,
                'type'      => $contentData['type'],
                'content'   => $contentData['content'],
                'order'     => $index + 1,
            ]);

            if ($contentData['type'] === 'quiz' && isset($contentData['options'])) {
                foreach ($contentData['options'] as $optionData) {
                    Options::create([
                        'lesson_content_id' => $content->id,
                        'option_text'       => $optionData['option_text'],
                        'is_correct'        => $optionData['is_correct'],
                    ]);
                }
            }
        }

        $lesson->load(['contents.options']);

        return response()->json([
            'status'  => 'success',
            'message' => 'Lesson successfully added',
            'data'    => $lesson,
        ], 201);
    }

    // D2: Delete Lesson (admin only)
    public function destroy(Request $request, $set_id, $lesson_id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'status'  => 'insufficient_permissions',
                'message' => 'Access forbidden',
            ], 403);
        }

        $set = Sets::find($set_id);

        if (!$set) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'Resource not found',
            ], 404);
        }

        $lesson = Lessons::where('id', $lesson_id)->where('set_id', $set->id)->first();

        if (!$lesson) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'Resource not found',
            ], 404);
        }

        $lesson->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Lesson successfully deleted',
        ], 200);
    }
}