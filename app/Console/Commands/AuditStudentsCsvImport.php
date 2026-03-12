<?php

namespace App\Console\Commands;

use App\Models\Student;
use Illuminate\Console\Command;

class AuditStudentsCsvImport extends Command
{
    protected $signature = 'students:audit-csv-import
        {file : Absolute or relative path to CSV file}
        {--only-branch= : Filter CSV rows by branch (AJAH, FESTAC, IKEJA, AGEGE)}';

    protected $description = 'Compare CSV rows with database students and list missing/invalid entries.';

    public function handle(): int
    {
        $file = (string) $this->argument('file');

        if (! is_file($file) || ! is_readable($file)) {
            $this->error("CSV file is not readable: {$file}");

            return self::FAILURE;
        }

        $onlyBranchOption = $this->resolveOnlyBranchOption();
        if ($onlyBranchOption === false) {
            return self::FAILURE;
        }

        $onlyBranch = is_string($onlyBranchOption) ? $onlyBranchOption : null;

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

        $existingEmails = Student::query()
            ->whereNotNull('email')
            ->pluck('email')
            ->map(fn ($email) => strtolower(trim((string) $email)))
            ->filter()
            ->values()
            ->all();

        $emailLookup = array_fill_keys($existingEmails, true);

        $line = 1;
        $totalDataRows = 0;
        $consideredRows = 0;
        $presentRows = 0;
        $missingRows = 0;
        $invalidRows = 0;
        $branchFilteredRows = 0;

        $missing = [];
        $invalid = [];

        while (($row = fgetcsv($handle)) !== false) {
            $line++;

            if ($this->isSkippableRow($row)) {
                continue;
            }

            $totalDataRows++;

            $firstName = $this->cleanText($this->value($row, $headerMap, 'first_name'));
            $lastName = $this->cleanText($this->value($row, $headerMap, 'last_name'));
            $email = strtolower($this->cleanText($this->value($row, $headerMap, 'email')));
            $remark = $this->cleanText($this->value($row, $headerMap, 'remark'));
            $branch = $this->mapBranch($remark);

            if (is_string($onlyBranch) && $branch !== $onlyBranch) {
                $branchFilteredRows++;

                continue;
            }

            $consideredRows++;

            if ($firstName === '' || $lastName === '' || $email === '') {
                $invalidRows++;
                $invalid[] = [
                    'line' => $line,
                    'name' => trim($firstName . ' ' . $lastName),
                    'email' => $email,
                    'reason' => 'Missing first name, last name, or email',
                ];

                continue;
            }

            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $invalidRows++;
                $invalid[] = [
                    'line' => $line,
                    'name' => trim($firstName . ' ' . $lastName),
                    'email' => $email,
                    'reason' => 'Invalid email format',
                ];

                continue;
            }

            if (isset($emailLookup[$email])) {
                $presentRows++;

                continue;
            }

            $missingRows++;
            $missing[] = [
                'line' => $line,
                'name' => trim($firstName . ' ' . $lastName),
                'email' => $email,
                'branch' => $branch,
            ];
        }

        fclose($handle);

        $this->newLine();
        $this->info('CSV Audit Summary');
        $this->line("Total non-empty data rows: {$totalDataRows}");
        $this->line("Rows considered: {$consideredRows}");
        $this->line("Rows present in DB: {$presentRows}");
        $this->line("Rows missing in DB: {$missingRows}");
        $this->line("Rows invalid in CSV: {$invalidRows}");

        if ($onlyBranch !== null) {
            $this->line("Rows skipped by branch filter: {$branchFilteredRows}");
            $this->line("Branch filter: {$onlyBranch}");
        }

        if ($missingRows > 0) {
            $this->newLine();
            $this->warn('Missing rows (valid CSV rows not found in students table):');
            $this->table(['Line', 'Name', 'Email', 'Branch'], $missing);
        }

        if ($invalidRows > 0) {
            $this->newLine();
            $this->warn('Invalid rows (cannot be imported reliably):');
            $this->table(['Line', 'Name', 'Email', 'Reason'], $invalid);
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
            'email' => $normalized['email'] ?? null,
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

    private function cleanText(?string $value): string
    {
        $value = trim((string) $value);

        return preg_replace('/\s+/', ' ', $value) ?? $value;
    }

    private function isSkippableRow(array $row): bool
    {
        $joined = trim(implode('', array_map(fn ($item) => trim((string) $item), $row)));

        return $joined === '';
    }

    private function mapBranch(string $remark): string
    {
        $value = strtoupper($remark);

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
}
