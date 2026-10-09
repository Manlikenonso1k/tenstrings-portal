<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\StudentCourseFee;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CorrectPaymentAmount extends Command
{
    protected $signature = 'payments:correct-amount
        {payment : Payment ID, for example 128}
        {amount : Correct amount in naira, for example 180000}
        {--actor-id= : Optional user ID to record as the person approving this correction}
        {--confirm : Apply the correction. Without this option, only a preview is shown.}';

    protected $description = 'Safely correct a payment amount and reconcile the related student course-fee totals.';

    public function handle(): int
    {
        $paymentId = (int) $this->argument('payment');
        $correctAmount = (float) $this->argument('amount');

        if ($paymentId < 1 || $correctAmount <= 0) {
            $this->error('Payment ID and corrected amount must be positive numbers.');

            return self::FAILURE;
        }

        $payment = Payment::query()->with(['student', 'course'])->find($paymentId);

        if (! $payment) {
            $this->error("Payment #{$paymentId} was not found.");

            return self::FAILURE;
        }

        if (! $payment->course_id) {
            $this->error('This payment has no course attached and cannot be reconciled automatically.');

            return self::FAILURE;
        }

        $this->table(
            ['Payment', 'Student', 'Matric', 'Course', 'Current', 'Corrected'],
            [[
                $payment->payment_number,
                $payment->student?->full_name ?? 'Unknown',
                $payment->student?->student_number ?? 'Unknown',
                $payment->course?->name ?? 'Unknown',
                '₦' . number_format((float) $payment->amount_paid, 2),
                '₦' . number_format($correctAmount, 2),
            ]]
        );

        if (! $this->option('confirm')) {
            $this->warn('Preview only. No data was changed. Re-run with --confirm to apply the correction.');

            return self::SUCCESS;
        }

        $actor = $this->resolveActor();

        try {
            $result = DB::transaction(function () use ($paymentId, $correctAmount, $actor): array {
                $payment = Payment::query()->lockForUpdate()->findOrFail($paymentId);
                $student = $payment->student()->lockForUpdate()->firstOrFail();
                $courseFee = StudentCourseFee::query()
                    ->where('student_id', $student->id)
                    ->where('course_id', $payment->course_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $oldAmount = (float) $payment->amount_paid;

                $payment->forceFill([
                    'amount_paid' => $correctAmount,
                    'amount' => $correctAmount,
                ])->saveQuietly();

                $actualPaid = (float) Payment::query()
                    ->where('student_id', $student->id)
                    ->where('course_id', $payment->course_id)
                    ->where('status', 'success')
                    ->sum('amount_paid');

                $courseFee->forceFill([
                    'amount_paid' => $actualPaid,
                    'outstanding_balance' => max(0, (float) $courseFee->total_course_fee - $actualPaid),
                    'status' => $actualPaid >= (float) $courseFee->total_course_fee
                        ? 'paid'
                        : ($actualPaid > 0 ? 'partial' : 'pending'),
                ])->saveQuietly();

                $totals = StudentCourseFee::query()
                    ->where('student_id', $student->id)
                    ->selectRaw('COALESCE(SUM(total_course_fee), 0) as total_fee, COALESCE(SUM(amount_paid), 0) as paid, COALESCE(SUM(outstanding_balance), 0) as outstanding')
                    ->first();

                $student->forceFill([
                    'total_balance' => (float) $totals->total_fee,
                    'fees_paid' => (float) $totals->paid,
                    'balance_due' => (float) $totals->outstanding,
                ])->saveQuietly();

                $activity = activity()
                    ->performedOn($student)
                    ->withProperties([
                        'payment_id' => $payment->id,
                        'payment_number' => $payment->payment_number,
                        'student_name' => $student->full_name,
                        'student_number' => $student->student_number,
                        'old_amount' => $oldAmount,
                        'corrected_amount' => $correctAmount,
                    ]);

                if ($actor) {
                    $activity->causedBy($actor);
                }

                $activity->log('payment_amount_corrected');

                return [
                    'student' => $student,
                    'paid' => (float) $totals->paid,
                    'balance' => (float) $totals->outstanding,
                ];
            });
        } catch (\Throwable $exception) {
            $this->error('Correction failed: ' . $exception->getMessage());

            return self::FAILURE;
        }

        $this->info('Payment correction completed.');
        $this->line('Student: ' . $result['student']->full_name . ' (' . $result['student']->student_number . ')');
        $this->line('Total fees paid: ₦' . number_format($result['paid'], 2));
        $this->line('Balance due: ₦' . number_format($result['balance'], 2));

        return self::SUCCESS;
    }

    private function resolveActor(): ?User
    {
        $actorId = $this->option('actor-id');

        if ($actorId === null || $actorId === '') {
            return null;
        }

        $actor = User::query()->find((int) $actorId);

        if (! $actor) {
            throw new RuntimeException("Actor user #{$actorId} was not found.");
        }

        return $actor;
    }
}