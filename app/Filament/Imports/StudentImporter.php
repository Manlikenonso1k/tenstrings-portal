<?php

namespace App\Filament\Imports;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\User;
use App\Support\CourseCatalog;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class StudentImporter extends Importer
{
    protected static ?string $model = Student::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('first_name')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('middle_name')
                ->rules(['nullable', 'string', 'max:255']),
            ImportColumn::make('last_name')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),
            ImportColumn::make('phone')
                ->rules(['nullable', 'string', 'max:30'])
                ->castStateUsing(function (?string $state): string {
                    $state = preg_replace('/[^0-9+,]/', '', (string) $state) ?? '';

                    return trim($state) !== '' ? trim($state) : 'N/A';
                }),
            ImportColumn::make('address')
                ->rules(['nullable', 'string', 'max:1000']),
            ImportColumn::make('branch')
                ->rules(['nullable', 'in:AJAH BRANCH,AGEGE BRANCH,IKEJA BRANCH,FESTAC BRANCH'])
                ->castStateUsing(fn (?string $state): string => $state ? strtoupper(trim($state)) : 'IKEJA BRANCH'),
            ImportColumn::make('student_number')
                ->rules(['nullable', 'string', 'max:255']),
            ImportColumn::make('selected_course_name')
                ->label('Course')
                ->requiredMapping()
                ->rules(['required', 'in:' . implode(',', array_keys(CourseCatalog::courseOptions()))]),
            ImportColumn::make('duration')
                ->rules(['nullable', 'string', 'max:30']),
            ImportColumn::make('start_date')
                ->requiredMapping()
                ->rules(['required', 'date'])
                ->castStateUsing(function (mixed $state): string {
                    if (blank($state)) {
                        return now()->toDateString();
                    }

                    try {
                        return Carbon::parse((string) $state)->toDateString();
                    } catch (\Throwable) {
                        return (string) $state;
                    }
                }),
            ImportColumn::make('date_of_birth')
                ->rules(['nullable', 'date'])
                ->castStateUsing(function (mixed $state): ?string {
                    if (blank($state)) {
                        return null;
                    }

                    $normalized = preg_replace('/(\d+)(st|nd|rd|th)/i', '$1', (string) $state) ?? (string) $state;

                    try {
                        return Carbon::parse($normalized)->toDateString();
                    } catch (\Throwable) {
                        return (string) $state;
                    }
                }),
        ];
    }

    public function resolveRecord(): ?Student
    {
        return new Student();
    }

    protected function beforeValidate(): void
    {
        $email = strtolower(trim((string) ($this->data['email'] ?? '')));
        $studentNumber = trim((string) ($this->data['student_number'] ?? ''));

        if ($email !== '' && Student::query()->whereRaw('LOWER(email) = ?', [$email])->exists()) {
            throw new RowImportFailedException('Skipped duplicate: student email already exists.');
        }

        if ($studentNumber !== '' && Student::query()->where('student_number', $studentNumber)->exists()) {
            throw new RowImportFailedException('Skipped duplicate: matric number already exists.');
        }

        if ($email !== '') {
            $existingUser = User::query()->whereRaw('LOWER(email) = ?', [$email])->first();

            if ($existingUser && $existingUser->role !== 'student') {
                throw new RowImportFailedException('Skipped: user email exists with non-student role.');
            }
        }
    }

    protected function beforeSave(): void
    {
        $courseName = (string) ($this->data['selected_course_name'] ?? '');

        if ($courseName === '') {
            throw new RowImportFailedException('Skipped: course is required.');
        }

        $duration = (string) ($this->data['duration'] ?? '');
        if ($duration === '' || ! CourseCatalog::isValidDurationFor($courseName, $duration)) {
            $duration = CourseCatalog::defaultDurationFor($courseName) ?? '6 months';
        }

        $email = strtolower(trim((string) ($this->data['email'] ?? '')));

        $user = User::query()->where('email', $email)->first();
        if (! $user) {
            $user = User::query()->create([
                'name' => trim(($this->data['first_name'] ?? '') . ' ' . ($this->data['last_name'] ?? '')),
                'email' => $email,
                'phone' => (string) ($this->data['phone'] ?? 'N/A'),
                'role' => 'student',
                'password' => Hash::make(str()->password(10)),
            ]);
        }

        $this->data['duration'] = $duration;
        $this->data['selected_course_code'] = CourseCatalog::codeFor($courseName);
        $this->data['branch'] = (string) ($this->data['branch'] ?? 'IKEJA BRANCH');
        $this->data['registration_date'] = now()->toDateString();
        $this->data['status'] = 'active';
        $this->data['user_id'] = $user->id;

        if (blank($this->data['student_number'] ?? null)) {
            unset($this->data['student_number']);
        }

        if (blank($this->data['date_of_birth'] ?? null)) {
            $this->data['date_of_birth'] = null;
        }
    }

    protected function afterCreate(): void
    {
        $startDate = Carbon::parse((string) ($this->record->start_date ?? now()->toDateString()));
        $duration = (string) ($this->record->duration ?? '6 months');

        $endDate = $startDate->copy();
        if (str_contains($duration, '18')) {
            $endDate->addMonths(18);
        } elseif (str_contains($duration, 'year')) {
            $endDate->addYear();
        } elseif (str_contains($duration, '3')) {
            $endDate->addMonths(3);
        } else {
            $endDate->addMonths(6);
        }

        $enrollment = Enrollment::query()->create([
            'student_id' => $this->record->id,
            'enrollment_date' => now()->toDateString(),
            'intake_month' => strtoupper($startDate->format('F')),
            'start_date' => $startDate->toDateString(),
            'expected_end_date' => $endDate->toDateString(),
            'status' => 'ongoing',
        ]);

        $courseName = (string) $this->record->selected_course_name;
        $course = Course::query()->where('name', $courseName)->first();

        if ($course) {
            $enrollment->courses()->syncWithoutDetaching([$course->id]);
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $totalRows = (int) $import->total_rows;
        $successfulRows = (int) $import->successful_rows;
        $failedRows = (int) $import->getFailedRowsCount();

        $skippedDuplicateRows = $import->failedRows()
            ->where('validation_error', 'like', 'Skipped duplicate:%')
            ->count();

        $otherFailedRows = max(0, $failedRows - $skippedDuplicateRows);

        $body = 'Student import completed. '
            . number_format($totalRows) . ' rows processed, '
            . number_format($successfulRows) . ' imported, '
            . number_format($skippedDuplicateRows) . ' skipped as duplicates.';

        if ($otherFailedRows > 0) {
            $body .= ' ' . number_format($otherFailedRows) . ' rows failed validation. Download the failed rows CSV to fix and re-import.';
        }

        return $body;
    }

    public function getJobQueue(): ?string
    {
        return 'default';
    }
}
