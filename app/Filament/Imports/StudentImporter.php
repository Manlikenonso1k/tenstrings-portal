<?php

namespace App\Filament\Imports;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentCourseFee;
use App\Models\User;
use App\Support\CourseCatalog;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
                ->castStateUsing(fn (?string $state): string => self::normalizeBranch($state)),
            ImportColumn::make('student_number')
                ->rules(['nullable', 'string', 'max:255']),
            ImportColumn::make('selected_course_name')
                ->label('Course')
                ->requiredMapping()
                ->castStateUsing(fn (?string $state): string => self::normalizeCourseName($state))
                ->rules(['required', 'in:' . implode(',', array_keys(CourseCatalog::courseOptions()))]),
            ImportColumn::make('duration')
                ->castStateUsing(fn (?string $state): ?string => self::normalizeDuration($state))
                ->rules(['nullable', 'string', 'max:30']),
            ImportColumn::make('start_date')
                ->requiredMapping()
                ->rules(['required', 'date'])
                ->castStateUsing(function (mixed $state): string {
                    if (blank($state)) {
                        return now()->toDateString();
                    }

                    try {
                        return self::normalizeStartDate((string) $state);
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
            ImportColumn::make('sex')
                ->rules(['nullable', 'in:Male,Female'])
                ->castStateUsing(fn (?string $state): ?string => self::normalizeSex($state)),
            ImportColumn::make('fees_paid')
                ->requiredMapping()
                ->rules(['required', 'numeric', 'min:0'])
                ->castStateUsing(fn (mixed $state): float => self::normalizeMoney($state)),
            ImportColumn::make('balance_due')
                ->requiredMapping()
                ->rules(['required', 'numeric', 'min:0'])
                ->castStateUsing(fn (mixed $state): float => self::normalizeMoney($state)),
            ImportColumn::make('hostel_fee')
                ->rules(['nullable', 'numeric', 'min:0'])
                ->castStateUsing(fn (mixed $state): float => self::normalizeMoney($state)),
            ImportColumn::make('total_balance')
                ->requiredMapping()
                ->rules(['required', 'numeric', 'min:0'])
                ->castStateUsing(fn (mixed $state): float => self::normalizeMoney($state)),
        ];
    }

    public function resolveRecord(): ?Student
    {
        $studentNumber = trim((string) ($this->data['student_number'] ?? ''));
        $email = strtolower(trim((string) ($this->data['email'] ?? '')));
        $firstName = strtolower(trim((string) ($this->data['first_name'] ?? '')));
        $lastName = strtolower(trim((string) ($this->data['last_name'] ?? '')));
        $lookupPhone = self::normalizePhoneForLookup((string) ($this->data['phone'] ?? ''));

        if ($studentNumber !== '') {
            $matchByMatric = Student::query()
                ->where('student_number', $studentNumber)
                ->first();

            if ($matchByMatric) {
                return $matchByMatric;
            }
        }

        if ($email !== '') {
            $matchByEmail = Student::query()
                ->whereRaw('LOWER(email) = ?', [$email])
                ->first();

            if ($matchByEmail) {
                return $matchByEmail;
            }
        }

        if ($firstName !== '' && $lastName !== '' && $lookupPhone !== '') {
            $nameMatches = Student::query()
                ->whereRaw('LOWER(first_name) = ?', [$firstName])
                ->whereRaw('LOWER(last_name) = ?', [$lastName])
                ->get();

            foreach ($nameMatches as $candidate) {
                if (! $candidate instanceof Student) {
                    continue;
                }

                if (self::normalizePhoneForLookup((string) $candidate->phone) === $lookupPhone) {
                    return $candidate;
                }
            }
        }

        return new Student();
    }

    protected function beforeValidate(): void
    {
        $email = strtolower(trim((string) ($this->data['email'] ?? '')));
        $studentNumber = trim((string) ($this->data['student_number'] ?? ''));

        $this->data['email'] = $email;
        $this->data['student_number'] = $studentNumber;

        if ($email !== '') {
            $existingUser = User::query()->whereRaw('LOWER(email) = ?', [$email])->first();
            $recordUserId = $this->record?->user_id;

            if (
                $existingUser
                && $existingUser->role !== 'student'
                && (! $recordUserId || (int) $recordUserId !== (int) $existingUser->id)
            ) {
                throw new RowImportFailedException('Failed: email belongs to a non-student user role.');
            }
        }
    }

    protected function beforeSave(): void
    {
        $courseName = (string) ($this->data['selected_course_name'] ?? '');

        if ($courseName === '') {
            throw new RowImportFailedException('Failed: course is required.');
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
        } else {
            $user->forceFill([
                'name' => trim(($this->data['first_name'] ?? '') . ' ' . ($this->data['last_name'] ?? '')),
                'phone' => (string) ($this->data['phone'] ?? 'N/A'),
                'role' => 'student',
            ])->save();
        }

        $feesPaid = (float) ($this->data['fees_paid'] ?? 0);
        $balanceDue = (float) ($this->data['balance_due'] ?? 0);
        $hostelFee = (float) ($this->data['hostel_fee'] ?? 0);
        $totalBalance = (float) ($this->data['total_balance'] ?? 0);

        $this->data['duration'] = $duration;
        $this->data['selected_course_code'] = CourseCatalog::codeFor($courseName);
        $this->data['branch'] = (string) ($this->data['branch'] ?? 'IKEJA BRANCH');
        $this->data['registration_date'] = now()->toDateString();
        $this->data['status'] = 'active';
        $this->data['user_id'] = $user->id;
        $this->data['fees_paid'] = $feesPaid;
        $this->data['balance_due'] = $balanceDue;
        $this->data['hostel_fee'] = $hostelFee;
        $this->data['total_balance'] = $totalBalance;

        if (blank($this->data['student_number'] ?? null)) {
            unset($this->data['student_number']);
        }

        if (blank($this->data['date_of_birth'] ?? null)) {
            $this->data['date_of_birth'] = null;
        }

        if (blank($this->data['sex'] ?? null)) {
            $this->data['sex'] = null;
        }
    }

    protected function afterCreate(): void
    {
        Cache::increment($this->cacheCounterKey('created'));

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

        $this->syncFinancialRecords();
    }

    protected function afterUpdate(): void
    {
        Cache::increment($this->cacheCounterKey('updated'));
        $this->syncFinancialRecords();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $totalRows = (int) $import->total_rows;
        $successfulRows = (int) $import->successful_rows;
        $failedRows = (int) $import->getFailedRowsCount();
        $createdRows = (int) Cache::pull(static::cacheCounterKeyForImport($import->id, 'created'), 0);
        $updatedRows = (int) Cache::pull(static::cacheCounterKeyForImport($import->id, 'updated'), 0);
        $failedRowsDownloadUrl = $failedRows > 0
            ? route('filament.imports.failed-rows.download', ['import' => $import], absolute: false)
            : null;

        $failureReasons = $import->failedRows()
            ->whereNotNull('validation_error')
            ->pluck('validation_error')
            ->map(fn (string $reason): string => trim($reason))
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(3)
            ->map(fn (int $count, string $reason): string => "{$reason} ({$count})")
            ->implode('; ');

        $body = "✅ Total Successfully Processed: " . number_format($successfulRows)
            . " of " . number_format($totalRows) . " rows.\n"
            . "🔄 Records Updated (Duplicates handled): " . number_format($updatedRows) . ".\n"
            . "✅ Records Created: " . number_format($createdRows) . ".\n"
            . "❌ Records Failed: " . number_format($failedRows) . ".";

        if ($failureReasons !== '') {
            $body .= "\nWhy failed: {$failureReasons}.";
        }

        if ($failedRowsDownloadUrl !== null) {
            $body .= "\nDownload failed rows: {$failedRowsDownloadUrl}";
        }

        return $body;
    }

    public static function getCompletedNotificationTitle(Import $import): string
    {
        $failedRows = (int) $import->getFailedRowsCount();

        if ($failedRows === 0) {
            return 'Student import completed successfully';
        }

        return 'Student import completed with issues';
    }

    public function getJobQueue(): ?string
    {
        return 'default';
    }

    public function getJobConnection(): ?string
    {
        return 'sync';
    }

    public function getJobBatchName(): ?string
    {
        return 'Student CSV Import #' . $this->import->getKey();
    }

    private function syncFinancialRecords(): void
    {
        $course = Course::query()
            ->where('name', (string) $this->record->selected_course_name)
            ->first();

        $feesPaid = (float) ($this->record->fees_paid ?? 0);
        $balanceDue = (float) ($this->record->balance_due ?? 0);
        $totalBalance = (float) ($this->record->total_balance ?? 0);

        if ($course) {
            StudentCourseFee::query()->updateOrCreate(
                [
                    'student_id' => $this->record->id,
                    'course_id' => $course->id,
                ],
                [
                    'total_course_fee' => max($totalBalance, $feesPaid + $balanceDue),
                    'amount_paid' => $feesPaid,
                    'outstanding_balance' => $balanceDue,
                    'status' => $balanceDue <= 0 ? 'paid' : ($feesPaid > 0 ? 'partial' : 'pending'),
                ],
            );

            if ($feesPaid > 0) {
                Payment::query()->updateOrCreate(
                    [
                        'student_id' => $this->record->id,
                        'course_id' => $course->id,
                        'notes' => 'CSV_IMPORT_SNAPSHOT',
                    ],
                    [
                        'amount_paid' => $feesPaid,
                        'payment_date' => now()->toDateString(),
                        'payment_method' => 'transfer',
                        'payment_status' => $balanceDue <= 0 ? 'paid' : 'partial',
                        'receipt_number' => null,
                    ],
                );
            }
        }
    }

    private static function normalizeMoney(mixed $state): float
    {
        if ($state === null) {
            return 0.0;
        }

        $value = strtoupper(trim((string) $state));

        if ($value === '' || in_array($value, ['NIL', 'NILL', 'NONE', '-', 'N/A'], true)) {
            return 0.0;
        }

        $value = preg_replace('/[^0-9.\-]/', '', $value) ?? '';

        if ($value === '' || ! is_numeric($value)) {
            return 0.0;
        }

        return round((float) $value, 2);
    }

    private static function normalizeBranch(?string $state): string
    {
        $value = strtoupper(trim((string) $state));

        if ($value === '') {
            return 'IKEJA BRANCH';
        }

        if (str_contains($value, 'AJAH')) {
            return 'AJAH BRANCH';
        }

        if (str_contains($value, 'FESTAC')) {
            return 'FESTAC BRANCH';
        }

        if (str_contains($value, 'AGEGE')) {
            return 'AGEGE BRANCH';
        }

        return 'IKEJA BRANCH';
    }

    private static function normalizeSex(?string $state): ?string
    {
        $value = strtoupper(trim((string) $state));

        if ($value === '') {
            return null;
        }

        if (in_array($value, ['M', 'MALE'], true)) {
            return 'Male';
        }

        if (in_array($value, ['F', 'FEMALE'], true)) {
            return 'Female';
        }

        return null;
    }

    private static function normalizeCourseName(?string $state): string
    {
        $value = strtolower(trim((string) $state));

        if (str_contains($value, 'advanced diploma') && str_contains($value, 'production')) {
            return 'Advanced Diploma in Music Production';
        }

        if (str_contains($value, 'advanced diploma') && str_contains($value, 'performance')) {
            return 'Advanced Diploma in Music Performance';
        }

        if (str_contains($value, 'diploma') && str_contains($value, 'gospel')) {
            return 'Diploma in Gospel Music Performance';
        }

        if (str_contains($value, 'diploma') && (str_contains($value, 'audio') || str_contains($value, 'production'))) {
            return 'Diploma in Music Production';
        }

        if (str_contains($value, 'diploma') && str_contains($value, 'performance')) {
            return 'Diploma in Music Performance';
        }

        if (str_contains($value, 'guitar')) {
            return 'Certificate in Guitar';
        }

        if (str_contains($value, 'piano')) {
            return 'Certificate in Piano';
        }

        if (str_contains($value, 'voice') || str_contains($value, 'vocal')) {
            return 'Certificate in Voice';
        }

        if (str_contains($value, 'gospel')) {
            return 'Certificate in Gospel Music Performance';
        }

        if (str_contains($value, 'production') || str_contains($value, 'audio')) {
            return 'Certificate in Music Production';
        }

        return 'Certificate in Music Performance';
    }

    private static function normalizeDuration(?string $state): ?string
    {
        $value = strtolower(trim((string) $state));

        if ($value === '') {
            return null;
        }

        if (preg_match('/18\s*month/', $value)) {
            return '18 months';
        }

        if (preg_match('/1\s*year|12\s*month/', $value)) {
            return '1 year';
        }

        if (preg_match('/6\s*month/', $value)) {
            return '6 months';
        }

        if (preg_match('/3\s*month/', $value)) {
            return '3 months';
        }

        return null;
    }

    private static function normalizePhoneForLookup(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if ($digits === '') {
            return '';
        }

        // Compare by trailing local digits so 070... and 23470... resolve to same person.
        return strlen($digits) > 10 ? substr($digits, -10) : $digits;
    }

    private static function normalizeStartDate(string $state): string
    {
        $value = strtoupper(trim($state));

        if (preg_match('/([A-Z]{3,9})\s*[-\/]?\s*(\d{2,4})/', $value, $matches)) {
            $month = ucfirst(strtolower($matches[1]));
            $year = (int) $matches[2];

            if ($year < 100) {
                $year += 2000;
            }

            return Carbon::parse("1 {$month} {$year}")->toDateString();
        }

        return Carbon::parse($state)->toDateString();
    }

    private function cacheCounterKey(string $type): string
    {
        return static::cacheCounterKeyForImport($this->import->id, $type);
    }

    private static function cacheCounterKeyForImport(int|string $importId, string $type): string
    {
        return 'student-import:' . $importId . ':' . Str::slug($type);
    }
}
