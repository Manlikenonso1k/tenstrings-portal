<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\User;
use App\Support\CourseCatalog;
use App\Support\StudentMatricMailer;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ImportStudentsFromCsv extends Command
{
    protected $signature = 'students:import-csv
        {file : Absolute or relative path to CSV file}
        {--no-email : Do not send generated credentials email}
        {--send-email : Send generated credentials email during import}
        {--only-branch= : Restrict import to one branch: AJAH, FESTAC, IKEJA, AGEGE}';

    protected $description = 'Import students from a CSV, create portal accounts, and email generated passwords.';

    /**
     * @var array<int, array{line:int, student:string, email:string, reason:string}>
     */
    private array $placeholderEmails = [];

    public function handle(): int
    {
        $file = (string) $this->argument('file');
        $sendEmail = (bool) $this->option('send-email') && ! (bool) $this->option('no-email');
        $onlyBranchOption = $this->resolveOnlyBranchOption();

        if ($onlyBranchOption === false) {
            return self::FAILURE;
        }

        $onlyBranch = is_string($onlyBranchOption) ? $onlyBranchOption : null;

        if (! is_file($file) || ! is_readable($file)) {
            $this->error("CSV file is not readable: {$file}");

            return self::FAILURE;
        }

        $handle = fopen($file, 'rb');
        if (! $handle) {
            $this->error("Unable to open CSV file: {$file}");

            return self::FAILURE;
        }

        $header = fgetcsv($handle) ?: [];
        $headerMap = $this->buildHeaderMap($header);

        if (! isset($headerMap['first_name'], $headerMap['last_name'], $headerMap['email'])) {
            fclose($handle);
            $this->error('CSV header is invalid. Expected FIRST NAME, LAST NAME, and EMAIL columns.');

            return self::FAILURE;
        }

        $created = 0;
        $updated = 0;
        $skipped = 0;
        $emailed = 0;
        $line = 1;
        $skippedRows = [];

        while (($row = fgetcsv($handle)) !== false) {
            $line++;

            if ($this->isSkippableRow($row)) {
                continue;
            }

            $firstName = $this->cleanText($this->value($row, $headerMap, 'first_name'));
            $lastName = $this->cleanText($this->value($row, $headerMap, 'last_name'));
            $middleName = $this->cleanText($this->value($row, $headerMap, 'middle_name'));
            $emailRaw = $this->cleanText($this->value($row, $headerMap, 'email'));
            $email = $this->cleanAndRepairEmail($emailRaw);
            $usedPlaceholder = false;
            $placeholderReason = '';

            if ($email === '') {
                $usedPlaceholder = true;
                $placeholderReason = 'missing_email';
                $email = $this->generateUniquePlaceholderEmail($firstName, $lastName, null, $line);
            } elseif (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $usedPlaceholder = true;
                $placeholderReason = 'invalid_email';
                $email = $this->generateUniquePlaceholderEmail($firstName, $lastName, null, $line);
            }

            if ($firstName === '' || $lastName === '') {
                $skipped++;
                $this->warn("Line {$line}: skipped due to missing/invalid name or email.");
                $skippedRows[] = $this->buildSkippedRow($header, $row, $line, 'missing_or_invalid_name_or_email');

                continue;
            }

            try {
                $result = DB::transaction(function () use ($row, $headerMap, $firstName, $middleName, $lastName, $email, $line, $onlyBranch): array {
                    $existingStudent = Student::query()->where('email', $email)->first();

                    $existingUser = User::query()->where('email', $email)->first();
                    if (! $existingStudent && $existingUser && $existingUser->role !== 'student') {
                        return ['status' => 'conflict'];
                    }

                    $programRaw = $this->cleanText($this->value($row, $headerMap, 'program'));
                    $intakeRaw = $this->cleanText($this->value($row, $headerMap, 'duration_program'));
                    $phoneRaw = $this->cleanText($this->value($row, $headerMap, 'phone'));
                    $addressRaw = $this->cleanText($this->value($row, $headerMap, 'address'));
                    $dobRaw = $this->cleanText($this->value($row, $headerMap, 'date_of_birth'));
                    $sexRaw = $this->cleanText($this->value($row, $headerMap, 'sex'));
                    $remarkRaw = $this->cleanText($this->value($row, $headerMap, 'remark'));

                    $courseName = $this->mapCourseName($programRaw);
                    $duration = $this->resolveDuration($programRaw, $courseName);
                    $startDate = $this->resolveStartDate($intakeRaw);
                    $dob = $this->parseOptionalDate($dobRaw);
                    $sex = $this->normalizeSex($sexRaw);
                    $branch = $this->mapBranch($remarkRaw);

                    if (is_string($onlyBranch) && $branch !== $onlyBranch) {
                        return ['status' => 'branch_filtered'];
                    }

                    $phone = $this->normalizePhone($phoneRaw);

                    $plainPassword = $this->generatePassword();

                    if ($existingStudent) {
                        $user = $existingStudent->user;

                        if (! $user) {
                            if ($existingUser) {
                                $user = $existingUser;
                            } else {
                                $user = User::query()->create([
                                    'name' => trim("{$firstName} {$lastName}"),
                                    'email' => $email,
                                    'phone' => $phone,
                                    'role' => 'student',
                                    'password' => Hash::make($plainPassword),
                                ]);
                            }
                        }

                        $user->forceFill([
                            'name' => trim("{$firstName} {$lastName}"),
                            'email' => $email,
                            'phone' => $phone,
                            'role' => 'student',
                            'password' => Hash::make($plainPassword),
                        ])->save();

                        $existingStudent->fill([
                            'user_id' => $user->id,
                            'first_name' => $firstName,
                            'middle_name' => $middleName !== '' ? $middleName : null,
                            'last_name' => $lastName,
                            'selected_course_name' => $courseName,
                            'selected_course_code' => CourseCatalog::codeFor($courseName),
                            'duration' => $duration,
                            'email' => $email,
                            'phone' => $phone,
                            'address' => $addressRaw !== '' ? $addressRaw : null,
                            'branch' => $branch,
                            'date_of_birth' => $dob?->toDateString(),
                            'sex' => $sex,
                            'start_date' => $startDate->toDateString(),
                            'registration_date' => $existingStudent->registration_date ?? now()->toDateString(),
                            'status' => 'active',
                        ])->save();

                        $enrollment = $existingStudent->enrollments()->latest('id')->first();
                        if (! $enrollment) {
                            $enrollment = Enrollment::query()->create([
                                'student_id' => $existingStudent->id,
                                'enrollment_date' => now()->toDateString(),
                                'intake_month' => strtoupper($startDate->format('F')),
                                'start_date' => $startDate->toDateString(),
                                'expected_end_date' => $this->expectedEndDate($startDate, $duration)->toDateString(),
                                'status' => 'ongoing',
                            ]);
                        } else {
                            $enrollment->forceFill([
                                'intake_month' => strtoupper($startDate->format('F')),
                                'start_date' => $startDate->toDateString(),
                                'expected_end_date' => $this->expectedEndDate($startDate, $duration)->toDateString(),
                            ])->save();
                        }

                        $course = Course::query()->where('name', $courseName)->first();
                        if ($course) {
                            $enrollment->courses()->syncWithoutDetaching([$course->id]);
                        }

                        return [
                            'status' => 'updated',
                            'student' => $existingStudent->fresh(),
                            'password' => $plainPassword,
                        ];
                    }

                    if ($existingUser) {
                        $user = $existingUser;
                        $user->forceFill([
                            'name' => trim("{$firstName} {$lastName}"),
                            'email' => $email,
                            'phone' => $phone,
                            'role' => 'student',
                            'password' => Hash::make($plainPassword),
                        ])->save();
                    } else {
                        $user = User::query()->create([
                            'name' => trim("{$firstName} {$lastName}"),
                            'email' => $email,
                            'phone' => $phone,
                            'role' => 'student',
                            'password' => Hash::make($plainPassword),
                        ]);
                    }

                    $student = Student::query()->create([
                        'user_id' => $user->id,
                        'first_name' => $firstName,
                        'middle_name' => $middleName !== '' ? $middleName : null,
                        'last_name' => $lastName,
                        'selected_course_name' => $courseName,
                        'selected_course_code' => CourseCatalog::codeFor($courseName),
                        'duration' => $duration,
                        'email' => $email,
                        'phone' => $phone,
                        'address' => $addressRaw !== '' ? $addressRaw : null,
                        'branch' => $branch,
                        'date_of_birth' => $dob?->toDateString(),
                        'sex' => $sex,
                        'start_date' => $startDate->toDateString(),
                        'registration_date' => now()->toDateString(),
                        'status' => 'active',
                    ]);

                    $enrollment = Enrollment::query()->create([
                        'student_id' => $student->id,
                        'enrollment_date' => now()->toDateString(),
                        'intake_month' => strtoupper($startDate->format('F')),
                        'start_date' => $startDate->toDateString(),
                        'expected_end_date' => $this->expectedEndDate($startDate, $duration)->toDateString(),
                        'status' => 'ongoing',
                    ]);

                    $course = Course::query()->where('name', $courseName)->first();
                    if ($course) {
                        $enrollment->courses()->attach($course->id);
                    }

                    return [
                        'status' => 'created',
                        'student' => $student,
                        'password' => $plainPassword,
                    ];
                });

                if ($result['status'] === 'branch_filtered') {
                    $skipped++;
                    $skippedRows[] = $this->buildSkippedRow($header, $row, $line, 'filtered_by_only_branch_option');

                    continue;
                }

                if ($result['status'] === 'conflict') {
                    $skipped++;
                    $this->warn("Line {$line}: skipped, user {$email} exists with a non-student role.");
                    $skippedRows[] = $this->buildSkippedRow($header, $row, $line, 'existing_non_student_user_conflict');

                    continue;
                }

                if ($result['status'] === 'created') {
                    $created++;
                }

                if ($result['status'] === 'updated') {
                    $updated++;
                }

                /** @var Student $student */
                $student = $result['student'];
                $actionLabel = $result['status'] === 'updated' ? 'Updated' : 'Created';
                $this->info("{$actionLabel} {$student->student_number} for {$student->email}");

                if ($usedPlaceholder) {
                    $finalPlaceholderEmail = $this->generateUniquePlaceholderEmail($firstName, $lastName, $student->student_number, $line);

                    if ($student->email !== $finalPlaceholderEmail) {
                        $student->forceFill(['email' => $finalPlaceholderEmail])->save();
                        $student->user?->forceFill(['email' => $finalPlaceholderEmail])->save();
                    }

                    $student = $student->fresh();
                    $email = $finalPlaceholderEmail;

                    $this->placeholderEmails[] = [
                        'line' => $line,
                        'student' => trim("{$student->first_name} {$student->last_name}"),
                        'email' => $finalPlaceholderEmail,
                        'reason' => $placeholderReason,
                    ];
                }

                $this->notifyBranchForCredentials($student, (string) $result['password']);
                $emailed++;

                if ($sendEmail) {
                    try {
                        StudentMatricMailer::sendWithCredentials($student, (string) $result['password']);
                    } catch (Throwable $exception) {
                        Log::warning('Student credentials email could not be sent during CSV import.', [
                            'student_id' => $student->id,
                            'email' => $student->email,
                            'error' => $exception->getMessage(),
                        ]);

                        $this->warn("Email failed for {$student->email}: {$exception->getMessage()}");
                    }
                }
            } catch (Throwable $exception) {
                $skipped++;
                Log::error('CSV student import failed for a row.', [
                    'line' => $line,
                    'error' => $exception->getMessage(),
                ]);
                $skippedRows[] = $this->buildSkippedRow($header, $row, $line, 'import_failed: ' . $exception->getMessage());

                $this->error("Line {$line}: import failed - {$exception->getMessage()}");
            }
        }

        fclose($handle);

        $skippedRowsPath = $this->exportSkippedRows($header, $skippedRows);
        $placeholderReportPath = $this->exportPlaceholderEmails();

        $this->newLine();
        $this->info("Import completed. Created: {$created}, Updated: {$updated}, Skipped: {$skipped}, Emails sent: {$emailed}");
        $this->line("Skipped rows report: {$skippedRowsPath}");

        if ($placeholderReportPath !== null) {
            $this->line("Placeholder emails report: {$placeholderReportPath}");
            $this->table(
                ['Line', 'Student', 'Placeholder Email', 'Reason'],
                array_map(
                    fn (array $item): array => [
                        (string) $item['line'],
                        $item['student'],
                        $item['email'],
                        $item['reason'],
                    ],
                    $this->placeholderEmails
                )
            );
        }

        return self::SUCCESS;
    }

    private function buildHeaderMap(array $header): array
    {
        $normalized = [];
        foreach ($header as $index => $label) {
            $normalized[$this->normalizeHeader((string) $label)] = $index;
        }

        return [
            'first_name' => $normalized['first_name'] ?? null,
            'last_name' => $normalized['last_name'] ?? null,
            'middle_name' => $normalized['middle_name'] ?? null,
            'email' => $normalized['email'] ?? null,
            'date_of_birth' => $normalized['date_of_birth'] ?? null,
            'sex' => $normalized['sex'] ?? ($normalized['gender'] ?? null),
            'address' => $normalized['address'] ?? null,
            'phone' => $normalized['phone_no'] ?? ($normalized['phone'] ?? null),
            'program' => $normalized['program'] ?? null,
            'duration_program' => $normalized['duration_of_program'] ?? null,
            'remark' => $normalized['remark'] ?? null,
        ];
    }

    private function normalizeHeader(string $value): string
    {
        $value = strtolower(trim($value));
        $value = str_replace(['.', '/', '-', '(', ')'], ' ', $value);
        $value = preg_replace('/\s+/', '_', $value) ?? $value;

        return trim((string) $value, '_');
    }

    private function value(array $row, array $map, string $key): string
    {
        $index = $map[$key] ?? null;

        if ($index === null) {
            return '';
        }

        return (string) ($row[$index] ?? '');
    }

    private function isSkippableRow(array $row): bool
    {
        $joined = trim(implode('', array_map(fn ($item) => trim((string) $item), $row)));

        return $joined === '';
    }

    private function cleanText(?string $value): string
    {
        $value = trim((string) $value);

        return preg_replace('/\s+/', ' ', $value) ?? $value;
    }

    private function cleanAndRepairEmail(?string $value): string
    {
        $email = strtolower(trim((string) $value));
        $email = preg_replace('/\s+/', '', $email) ?? $email;
        $email = preg_replace('/(?<=\w),(?=\w)/', '.', $email) ?? $email;

        return $email;
    }

    private function generateUniquePlaceholderEmail(string $firstName, string $lastName, ?string $studentNumber, int $line): string
    {
        $base = Str::slug(trim("{$firstName} {$lastName}"), '_');
        if ($base === '') {
            $base = 'student';
        }

        $suffix = $studentNumber !== null
            ? strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $studentNumber) ?? '')
            : 'line' . $line;

        if ($suffix === '') {
            $suffix = 'line' . $line;
        }

        $candidate = "{$base}_{$suffix}@tenstrings.org";
        $increment = 1;

        while (
            User::query()->where('email', $candidate)->exists() ||
            Student::query()->where('email', $candidate)->exists()
        ) {
            $candidate = "{$base}_{$suffix}_{$increment}@tenstrings.org";
            $increment++;
        }

        return $candidate;
    }

    private function notifyBranchForCredentials(Student $student, string $plainPassword): void
    {
        StudentMatricMailer::sendBranchCredentials($student, $plainPassword);
    }

    private function normalizePhone(string $value): string
    {
        $cleaned = preg_replace('/[^0-9+,]/', '', $value) ?? $value;

        return trim($cleaned) !== '' ? trim($cleaned) : 'N/A';
    }

    private function mapBranch(string $remark): string
    {
        $value = strtoupper($remark);

        if (Str::contains($value, 'AJAH')) {
            return 'AJAH BRANCH';
        }

        if (Str::contains($value, 'FESTAC')) {
            return 'FESTAC BRANCH';
        }

        if (Str::contains($value, 'AGEGE')) {
            return 'AGEGE BRANCH';
        }

        return 'IKEJA BRANCH';
    }

    /**
     * @return string|false|null
     */
    private function resolveOnlyBranchOption(): string|false|null
    {
        $value = strtoupper(trim((string) $this->option('only-branch')));

        if ($value === '') {
            return null;
        }

        $allowed = [
            'AJAH' => 'AJAH BRANCH',
            'FESTAC' => 'FESTAC BRANCH',
            'IKEJA' => 'IKEJA BRANCH',
            'AGEGE' => 'AGEGE BRANCH',
        ];

        if (! isset($allowed[$value])) {
            $this->error('Invalid --only-branch value. Use one of: AJAH, FESTAC, IKEJA, AGEGE.');

            return false;
        }

        return $allowed[$value];
    }

    private function mapCourseName(string $program): string
    {
        $value = strtolower($program);

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

    private function resolveDuration(string $program, string $courseName): string
    {
        $value = strtolower($program);

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

        $default = CourseCatalog::defaultDurationFor($courseName);
        if ($default !== null) {
            return $default;
        }

        // Certificate tracks can be either 3 or 6 months; defaulting to 6 months for safer scheduling.
        return '6 months';
    }

    private function resolveStartDate(string $durationOfProgram): Carbon
    {
        $value = strtoupper(trim($durationOfProgram));

        if (preg_match('/([A-Z]{3,9})\s*[-\/]?\s*(\d{2,4})/', $value, $matches)) {
            $month = ucfirst(strtolower($matches[1]));
            $year = (int) $matches[2];
            if ($year < 100) {
                $year += 2000;
            }

            try {
                return Carbon::parse("1 {$month} {$year}")->startOfDay();
            } catch (Throwable) {
                // Fall through to default below.
            }
        }

        return Carbon::now()->startOfMonth();
    }

    private function parseOptionalDate(string $value): ?Carbon
    {
        if (trim($value) === '' || is_numeric($value) && (int) $value < 1900) {
            return null;
        }

        $normalized = preg_replace('/(\d+)(st|nd|rd|th)/i', '$1', $value) ?? $value;

        try {
            return Carbon::parse($normalized)->startOfDay();
        } catch (Throwable) {
            return null;
        }
    }

    private function normalizeSex(string $value): ?string
    {
        $normalized = strtoupper(trim($value));

        if ($normalized === '') {
            return null;
        }

        if (in_array($normalized, ['M', 'MALE'], true)) {
            return 'Male';
        }

        if (in_array($normalized, ['F', 'FEMALE'], true)) {
            return 'Female';
        }

        return null;
    }

    private function expectedEndDate(Carbon $startDate, string $duration): Carbon
    {
        $endDate = $startDate->copy();

        if (str_contains($duration, '18')) {
            return $endDate->addMonths(18);
        }

        if (str_contains($duration, 'year')) {
            return $endDate->addYear();
        }

        if (str_contains($duration, '3')) {
            return $endDate->addMonths(3);
        }

        return $endDate->addMonths(6);
    }

    private function generatePassword(): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';

        return Str::of(str_repeat('x', 10))
            ->replaceMatches('/x/', fn () => $alphabet[random_int(0, strlen($alphabet) - 1)])
            ->toString();
    }

    private function buildSkippedRow(array $header, array $row, int $line, string $reason): array
    {
        $record = [
            '__line' => $line,
            '__reason' => $reason,
        ];

        foreach ($header as $index => $column) {
            $record[(string) $column] = (string) ($row[$index] ?? '');
        }

        return $record;
    }

    private function exportSkippedRows(array $header, array $skippedRows): string
    {
        $directory = 'imports/reports';
        Storage::disk('local')->makeDirectory($directory);

        $filename = 'skipped_rows_' . now()->format('Ymd_His') . '.csv';
        $relativePath = $directory . '/' . $filename;
        $absolutePath = Storage::disk('local')->path($relativePath);

        $file = fopen($absolutePath, 'wb');
        if (! $file) {
            throw new \RuntimeException('Unable to write skipped rows report.');
        }

        $outputHeader = array_merge(['__line', '__reason'], array_map(fn ($value) => (string) $value, $header));
        fputcsv($file, $outputHeader);

        foreach ($skippedRows as $record) {
            $line = [];
            foreach ($outputHeader as $column) {
                $line[] = (string) ($record[$column] ?? '');
            }

            fputcsv($file, $line);
        }

        fclose($file);

        return $absolutePath;
    }

    private function exportPlaceholderEmails(): ?string
    {
        if ($this->placeholderEmails === []) {
            return null;
        }

        $directory = 'imports/reports';
        Storage::disk('local')->makeDirectory($directory);

        $filename = 'placeholder_emails_' . now()->format('Ymd_His') . '.csv';
        $relativePath = $directory . '/' . $filename;
        $absolutePath = Storage::disk('local')->path($relativePath);

        $file = fopen($absolutePath, 'wb');
        if (! $file) {
            throw new \RuntimeException('Unable to write placeholder emails report.');
        }

        fputcsv($file, ['line', 'student', 'placeholder_email', 'reason']);

        foreach ($this->placeholderEmails as $row) {
            fputcsv($file, [$row['line'], $row['student'], $row['email'], $row['reason']]);
        }

        fclose($file);

        return $absolutePath;
    }
}
