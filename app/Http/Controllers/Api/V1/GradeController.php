<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * GET /api/v1/grades
     *
     * Returns all grades for the authenticated student, paginated.
     */
    public function index(Request $request): JsonResponse
    {
        $student = $request->user()->student;

        abort_if(! $student, 404, 'Student profile not found.');

        $grades = Grade::where('student_id', $student->id)
            ->with('course:id,name,code')
            ->orderBy('date_recorded', 'desc')
            ->paginate(20);

        return response()->json([
            'data' => $grades->map(fn ($grade) => [
                'id'              => $grade->id,
                'course'          => [
                    'id'   => $grade->course?->id,
                    'name' => $grade->course?->name,
                    'code' => $grade->course?->code,
                ],
                'assessment_type' => $grade->assessment_type,
                'score'           => (float) $grade->score,
                'maximum_score'   => (float) $grade->maximum_score,
                'percentage'      => (float) $grade->percentage,
                'grade_letter'    => $grade->grade_letter,
                'date_recorded'   => $grade->date_recorded?->toDateString(),
            ]),
            'meta' => [
                'current_page' => $grades->currentPage(),
                'last_page'    => $grades->lastPage(),
                'total'        => $grades->total(),
                'per_page'     => $grades->perPage(),
            ],
        ]);
    }

    /**
     * GET /api/v1/grades/summary
     *
     * Returns aggregated grade statistics for the student.
     */
    public function summary(Request $request): JsonResponse
    {
        $student = $request->user()->student;

        abort_if(! $student, 404, 'Student profile not found.');

        $grades = Grade::where('student_id', $student->id)->get();

        if ($grades->isEmpty()) {
            return response()->json([
                'data' => [
                    'total_assessments'  => 0,
                    'average_percentage' => 0,
                    'highest_score'      => 0,
                    'lowest_score'       => 0,
                    'by_type'            => [],
                ],
            ]);
        }

        $byType = $grades->groupBy('assessment_type')->map(fn ($group) => [
            'count'              => $group->count(),
            'average_percentage' => round($group->avg('percentage'), 2),
        ]);

        return response()->json([
            'data' => [
                'total_assessments'  => $grades->count(),
                'average_percentage' => round($grades->avg('percentage'), 2),
                'highest_score'      => round($grades->max('percentage'), 2),
                'lowest_score'       => round($grades->min('percentage'), 2),
                'by_type'            => $byType,
            ],
        ]);
    }

    /**
     * GET /api/v1/grades/courses/{course}
     *
     * Returns all grades for the student filtered by a specific course.
     */
    public function byCourse(Request $request, $courseId): JsonResponse
    {
        $student = $request->user()->student;

        abort_if(! $student, 404, 'Student profile not found.');

        $grades = Grade::where('student_id', $student->id)
            ->where('course_id', $courseId)
            ->with('course:id,name,code')
            ->orderBy('date_recorded', 'desc')
            ->get()
            ->map(fn ($grade) => [
                'id'              => $grade->id,
                'assessment_type' => $grade->assessment_type,
                'score'           => (float) $grade->score,
                'maximum_score'   => (float) $grade->maximum_score,
                'percentage'      => (float) $grade->percentage,
                'grade_letter'    => $grade->grade_letter,
                'date_recorded'   => $grade->date_recorded?->toDateString(),
            ]);

        return response()->json(['data' => $grades]);
    }
}
