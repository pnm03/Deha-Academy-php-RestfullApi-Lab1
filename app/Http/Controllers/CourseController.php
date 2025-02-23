<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomApiException;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CourseController extends Controller
{
    // Lấy danh sách courses
    public function index()
    {
        return Course::all();
    }

    // Tạo mới course
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date'
        ]);

        $course = Course::create($validated);
        
        return response()->json($course, Response::HTTP_CREATED);
    }

    // Hiển thị chi tiết course
    // Trong method show()
    public function show($id)
    {
        $course = Course::find($id);
        
        if (!$course) {
            throw new CustomApiException('Course not found', Response::HTTP_NOT_FOUND);
        }
        
        return new CourseResource($course);
    }
    // Cập nhật course
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'start_date' => 'sometimes|date'
        ]);

        $course->update($validated);
        
        return response()->json($course);
    }

    // Xóa course
    public function destroy(Course $course)
    {
        $course->delete();
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}