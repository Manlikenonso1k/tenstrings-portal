<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Console\Command;
use RuntimeException;

class DeletePaymentAndReconcile extends Command
{
    protected $signature = 'payments:delete-and-reconcile
        {payment : Payment ID to remove, for example 128}
        {--actor-id= : Optional user ID to record as the person approving this deletion}
        {--confirm : Delete the payment. Without this option, only a preview is shown.}';

    protected $description = 'Safely delete a duplicate payment and automatically reconcile the affected student balance.';

    public function handle(): int
    {
        $payment = Payment::query()->with(['student', 'course'])->find((int) $this->argument('payment'));

        if (! $payment) {
            $this->error('Payment was not found.');

            return self::FAILURE;
        }

        $this->table(
            ['Payment', 'Receipt', 'Student', 'Matric', 'Course', 'Amount'],
            [[
                $payment->payment_number,
                $payment->receipt_number,
                $payment->student?->full_name ?? 'Unknown',
                $payment->student?->student_number ?? 'Unknown',
                $payment->course?->name ?? 'No course',
                '₦' . number_format((float) $payment->amount_paid, 2),
            ]]
        );

        if (! $this->option('confirm')) {
            $this->warn('Preview only. No data was deleted. Re-run with --confirm to delete this payment and reconcile balances.');

            return self::SUCCESS;
        }

        try {
            $actor = $this->resolveActor();
            $student = $payment->student;

            $activity = activity()
                ->performedOn($student)
                ->withProperties([
                    'payment_id' => $payment->id,
                    'payment_number' => $payment->payment_number,
                    'receipt_number' => $payment->receipt_number,
                    'student_name' => $student?->full_name,
                    'student_number' => $student?->student_number,
                    'deleted_amount' => (float) $payment->amount_paid,
                ]);

            if ($actor) {
                $activity->causedBy($actor);
            }

            $activity->log('duplicate_payment_deletion_approved');
            $payment->delete();
        } catch (\Throwable $exception) {
            $this->error('Deletion failed: ' . $exception->getMessage());

            return self::FAILURE;
        }

        $student?->refresh();

        $this->info('Payment deleted and student balance reconciled.');
        $this->line('Student: ' . ($student?->full_name ?? 'Unknown') . ' (' . ($student?->student_number ?? 'Unknown') . ')');
        $this->line('Total fees paid: ₦' . number_format((float) ($student?->fees_paid ?? 0), 2));
        $this->line('Balance due: ₦' . number_format((float) ($student?->balance_due ?? 0), 2));

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