<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Services\Payments\QuarterResolver;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function afterCreate(): void
    {
        $student = $this->record;

        // Skip CSV imports - they carry their own financial snapshot
        if (($student->created_via ?? 'dashboard') === 'csv') {
            return;
        }

        // Try to find the course by selected name
        $course = \App\Models\Course::query()
            ->where('name', (string) $student->selected_course_name)
            ->first();

        if (! $course) {
            return;
        }

        // Create a payment advice snapshot so the portal shows a balance
        $quarterResolver = app(QuarterResolver::class);
        $currentQuarter = $quarterResolver->currentQuarter();
        [$quarterLabel, $quarterYear] = explode('-', $currentQuarter, 2);
        $quarterMonth = match ($quarterLabel) {
            'Q1' => 2,
            'Q2' => 5,
            'Q3' => 8,
            default => 11,
        };

        \App\Models\PaymentAdvice::query()->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'quarter_month' => $quarterMonth,
            'year' => (int) $quarterYear,
            'quarter_name' => $currentQuarter,
            'amount' => (float) ($course->course_fee ?? 0),
            'status' => 'pending',
            'generated_at' => now(),
        ]);
        
        // Ensure a StudentCourseFee exists (so fee generation and outstanding calculations work)
        $existingFee = \App\Models\StudentCourseFee::query()
            ->where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->first();

        if (! $existingFee) {
            $courseFee = (float) ($course->course_fee ?? 0);
            $durationMonths = (int) ($course->duration_months ?? 0);

            if ($durationMonths < 12) {
                $requiredAmount = $courseFee;
            } else {
                $requiredAmount = round($courseFee * 0.7, 2);
            }

            \App\Models\StudentCourseFee::query()->create([
                'student_id' => $student->id,
                'course_id' => $course->id,
                'total_course_fee' => $courseFee,
                'amount_paid' => 0,
                'outstanding_balance' => $requiredAmount,
                'status' => 'pending',
            ]);
        }

        // Update student's financial snapshot from StudentCourseFee totals
        $totals = \App\Models\StudentCourseFee::query()
            ->where('student_id', $student->id)
            ->selectRaw('COALESCE(SUM(total_course_fee), 0) as total_fee, COALESCE(SUM(amount_paid), 0) as paid, COALESCE(SUM(outstanding_balance), 0) as outstanding')
            ->first();

        $student->update([
            'total_balance' => (float) ($totals->total_fee ?? 0),
            'fees_paid' => (float) ($totals->paid ?? 0),
            'balance_due' => (float) ($totals->outstanding ?? 0),
        ]);
    }
}
