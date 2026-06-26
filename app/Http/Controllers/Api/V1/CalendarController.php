<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * GET /api/v1/calendar
     *
     * Returns upcoming calendar events for the authenticated student.
     * Returns both student-specific events and general course events.
     *
     * Optional query parameters:
     *  - from: date (Y-m-d), defaults to today
     *  - to:   date (Y-m-d), defaults to 30 days from now
     */
    public function index(Request $request): JsonResponse
    {
        $student = $request->user()->student;

        abort_if(! $student, 404, 'Student profile not found.');

        $from = $request->date('from') ?? now()->startOfDay();
        $to   = $request->date('to')   ?? now()->addDays(30)->endOfDay();

        $events = CalendarEvent::where(function ($query) use ($student) {
                // Events specifically for this student
                $query->where('student_id', $student->id);
            })
            ->orWhere(function ($query) use ($student) {
                // General course-level events (no specific student targeted)
                $query->whereNull('student_id')->whereNotNull('course_id');
            })
            ->orWhere(function ($query) {
                // School-wide events (no student, no course filter)
                $query->whereNull('student_id')->whereNull('course_id');
            })
            ->whereBetween('start_at', [$from, $to])
            ->with('course:id,name,code')
            ->orderBy('start_at')
            ->get()
            ->map(fn ($event) => [
                'id'          => $event->id,
                'title'       => $event->title,
                'description' => $event->description,
                'event_type'  => $event->event_type, // class|assignment|payment|other
                'start_at'    => $event->start_at->toIso8601String(),
                'end_at'      => $event->end_at?->toIso8601String(),
                'course'      => $event->course ? [
                    'id'   => $event->course->id,
                    'name' => $event->course->name,
                ] : null,
            ]);

        return response()->json(['data' => $events]);
    }
}
