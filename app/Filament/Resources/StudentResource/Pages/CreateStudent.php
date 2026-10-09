<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Services\Payments\QuarterResolver;
use App\Support\StudentMatricMailer;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

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
        $startDate = $student->start_date ?? now();
        $currentQuarter = $quarterResolver->currentQuarter($startDate->toImmutable());
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

        $this->sendPortalCredentials($student);
    }

    private function sendPortalCredentials(\App\Models\Student $student): void
    {
        $user = $student->user;

        if (! $user) {
            Log::warning('Student portal credentials could not be sent because no user account is linked.', [
                'student_id' => $student->id,
                'email' => $student->email,
            ]);

            return;
        }

        $plainPassword = Str::password(12);

        try {
            $user->forceFill([
                'password' => Hash::make($plainPassword),
            ])->save();

            StudentMatricMailer::sendWithCredentials($student, $plainPassword);

            Notification::make()
                ->title('Student portal credentials sent')
                ->body('Login details have been emailed to ' . $student->email)
                ->success()
                ->send();
        } catch (Throwable $exception) {
            Log::warning('Student portal credentials could not be sent after creation.', [
                'student_id' => $student->id,
                'email' => $student->email,
                'error' => $exception->getMessage(),
            ]);

            Notification::make()
                ->title('Student created, but email was not sent')
                ->body('Use Resend Credentials from the student list after checking the email setup.')
                ->warning()
                ->send();
        }
    }
}
