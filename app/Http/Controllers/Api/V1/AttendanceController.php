<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * GET /api/v1/attendance
     *
     * Returns the student's paginated attendance records.
     */
    public function index(Request $request): JsonResponse
    {
        $student = $request->user()->student;

        abort_if(! $student, 404, 'Student profile not found.');

        $records = Attendance::where('student_id', $student->id)
            ->with('course:id,name,code')
            ->orderBy('attendance_date', 'desc')
            ->paginate(30);

        return response()->json([
            'data' => $records->map(fn ($record) => [
                'id'               => $record->id,
                'course'           => [
                    'id'   => $record->course?->id,
                    'name' => $record->course?->name,
                    'code' => $record->course?->code,
                ],
                'attendance_date'  => $record->attendance_date?->toDateString(),
                'status'           => $record->status, // present | absent | late | excused
                'instructor_notes' => $record->instructor_notes,
            ]),
            'meta' => [
                'current_page' => $records->currentPage(),
                'last_page'    => $records->lastPage(),
                'total'        => $records->total(),
                'per_page'     => $records->perPage(),
            ],
        ]);
    }

    /**
     * GET /api/v1/attendance/summary
     *
     * Returns attendance summary counts grouped by status.
     */
    public function summary(Request $request): JsonResponse
    {
        $student = $request->user()->student;

        abort_if(! $student, 404, 'Student profile not found.');

        $records = Attendance::where('student_id', $student->id)->get();

        $total   = $records->count();
        $present = $records->where('status', 'present')->count();
        $absent  = $records->where('status', 'absent')->count();
        $late    = $records->where('status', 'late')->count();
        $excused = $records->where('status', 'excused')->count();

        return response()->json([
            'data' => [
                'total'              => $total,
                'present'            => $present,
                'absent'             => $absent,
                'late'               => $late,
                'excused'            => $excused,
                'attendance_rate'    => $total > 0
                    ? round((($present + $late) / $total) * 100, 1)
                    : 0,
            ],
        ]);
    }
}
