<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * GET /api/v1/courses
     *
     * Returns active courses. For students, this shows all available courses.
     */
    public function index(Request $request): JsonResponse
    {
        $courses = Course::where('is_active', true)
            ->select(['id', 'code', 'name', 'duration_months', 'duration_label', 'course_fee', 'description'])
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $courses]);
    }

    /**
     * GET /api/v1/courses/{course}
     *
     * Returns a single course with its modules (without lesson content for performance).
     */
    public function show(Request $request, Course $course): JsonResponse
    {
        $course->load(['modules' => function ($query) {
            $query->select(['id', 'course_id', 'title', 'description', 'order'])
                ->orderBy('order')
                ->withCount('lessons');
        }]);

        return response()->json([
            'data' => [
                'id'              => $course->id,
                'code'            => $course->code,
                'name'            => $course->name,
                'duration_months' => $course->duration_months,
                'duration_label'  => $course->duration_label,
                'course_fee'      => (float) $course->course_fee,
                'description'     => $course->description,
                'modules'         => $course->modules,
            ],
        ]);
    }

    /**
     * GET /api/v1/courses/{course}/modules
     *
     * Returns all modules for a course with unlocked status for the student.
     */
    public function modules(Request $request, Course $course): JsonResponse
    {
        $user    = $request->user();
        $modules = $course->modules()->with('lessons:id,module_id,title,order')->get();

        $unlockedIds = $user->unlockedModuleIdsForCourse($course);

        $data = $modules->map(fn ($module) => [
            'id'           => $module->id,
            'title'        => $module->title,
            'description'  => $module->description,
            'order'        => $module->order,
            'lesson_count' => $module->lessons->count(),
            'is_unlocked'  => in_array($module->id, $unlockedIds),
        ]);

        return response()->json(['data' => $data]);
    }

    /**
     * GET /api/v1/courses/{course}/modules/{module}/lessons
     *
     * Returns lessons for a module. Checks access permission first.
     */
    public function lessons(Request $request, Course $course, $moduleId): JsonResponse
    {
        $module = $course->modules()->findOrFail($moduleId);

        if (! $request->user()->canAccessModule($module)) {
            return response()->json([
                'message' => 'This module is locked. Complete the previous module to unlock it.',
            ], 403);
        }

        $completedIds = $request->user()
            ->completedLessons()
            ->pluck('lessons.id')
            ->all();

        $lessons = $module->lessons()
            ->select(['id', 'module_id', 'title', 'order'])
            ->orderBy('order')
            ->get()
            ->map(fn ($lesson) => [
                'id'           => $lesson->id,
                'title'        => $lesson->title,
                'order'        => $lesson->order,
                'is_completed' => in_array($lesson->id, $completedIds),
            ]);

        return response()->json(['data' => $lessons]);
    }

    /**
     * GET /api/v1/courses/{course}/modules/{module}/lessons/{lesson}
     *
     * Returns a single lesson's full content.
     */
    public function lesson(Request $request, Course $course, $moduleId, $lessonId): JsonResponse
    {
        $module = $course->modules()->findOrFail($moduleId);

        if (! $request->user()->canAccessModule($module)) {
            return response()->json([
                'message' => 'This module is locked.',
            ], 403);
        }

        $lesson = $module->lessons()->findOrFail($lessonId);

        $isCompleted = $request->user()
            ->completedLessons()
            ->where('lessons.id', $lesson->id)
            ->exists();

        return response()->json([
            'data' => [
                'id'           => $lesson->id,
                'title'        => $lesson->title,
                'order'        => $lesson->order,
                'is_completed' => $isCompleted,
            ],
        ]);
    }

    /**
     * POST /api/v1/courses/{course}/modules/{module}/lessons/{lesson}/complete
     *
     * Marks a lesson as completed for the authenticated student.
     */
    public function completeLesson(Request $request, Course $course, $moduleId, $lessonId): JsonResponse
    {
        $module = $course->modules()->findOrFail($moduleId);
        $lesson = $module->lessons()->findOrFail($lessonId);

        $user = $request->user();

        // Check for duplicate completion
        $alreadyCompleted = $user->completedLessons()
            ->where('lessons.id', $lesson->id)
            ->exists();

        if (! $alreadyCompleted) {
            $user->completedLessons()->attach($lesson->id, [
                'completed_at' => now(),
            ]);
        }

        return response()->json([
            'message'      => $alreadyCompleted ? 'Lesson was already marked as complete.' : 'Lesson marked as complete.',
            'is_completed' => true,
        ]);
    }
}
