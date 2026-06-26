<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(Request $request, Course $course)
    {
        $course->loadMissing(['modules.lessons']);

        $user = $request->user();

        return view('courses.index', [
            'course' => $course,
            'modules' => $course->modules,
            'unlockedModuleIds' => $user?->unlockedModuleIdsForCourse($course) ?? [],
            'completedLessonIds' => $user?->completedLessons()
                ->whereHas('module', function ($query) use ($course): void {
                    $query->where('course_id', $course->getKey());
                })
                ->pluck('lessons.id')
                ->all() ?? [],
        ]);
    }

    public function show(Request $request, Course $course, CourseModule $module, Lesson $lesson)
    {
        abort_unless($lesson->module_id === $module->getKey(), 404);
        abort_unless($module->course_id === $course->getKey(), 404);

        return view('courses.lessons.show', [
            'course' => $course,
            'module' => $module->loadMissing('lessons'),
            'lesson' => $lesson,
            'unlockedModuleIds' => $request->user()?->unlockedModuleIdsForCourse($course) ?? [],
        ]);
    }
}
