<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CopySqliteToMysql extends Command
{
    protected $signature = 'portal:sqlite-to-mysql
        {--sqlite= : Absolute path to the sqlite file. Defaults to database/database.sqlite}
        {--sqlite-connection=sqlite : Source connection name}
        {--mysql-connection=mysql : Target connection name}
        {--exclude-migrations : Skip copying the migrations table}';

    protected $description = 'Copy Laravel data from SQLite to MySQL while preserving IDs and keys.';

    public function handle(): int
    {
        $sqliteConnection = (string) $this->option('sqlite-connection');
        $mysqlConnection = (string) $this->option('mysql-connection');
        $sqlitePath = (string) ($this->option('sqlite') ?: database_path('database.sqlite'));

        if (! is_file($sqlitePath)) {
            $this->error("SQLite file not found: {$sqlitePath}");

            return self::FAILURE;
        }

        Config::set("database.connections.{$sqliteConnection}.database", $sqlitePath);

        DB::purge($sqliteConnection);
        DB::purge($mysqlConnection);

        $source = DB::connection($sqliteConnection);
        $target = DB::connection($mysqlConnection);

        $this->info("Source sqlite: {$sqlitePath}");
        $this->info("Target mysql database: " . $target->getDatabaseName());

        $sqliteTables = collect($source->select("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%'"))
            ->map(fn ($row) => (string) $row->name)
            ->filter(function (string $table): bool {
                return ! in_array($table, ['cache', 'cache_locks'], true);
            })
            ->values();

        if ($this->option('exclude-migrations')) {
            $sqliteTables = $sqliteTables->reject(fn (string $table) => $table === 'migrations')->values();
        }

        $mysqlTables = collect($target->select('SHOW TABLES'))
            ->map(function (object $row): string {
                return (string) array_values((array) $row)[0];
            })
            ->values();

        $tables = $sqliteTables->filter(fn (string $table) => $mysqlTables->contains($table))->values();

        if ($tables->isEmpty()) {
            $this->warn('No overlapping tables were found between sqlite and mysql.');

            return self::SUCCESS;
        }

        $this->line('');
        $this->line('Copy order:');
        foreach ($tables as $table) {
            $this->line(" - {$table}");
        }
        $this->line('');

        $target->statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            foreach ($tables as $table) {
                $this->copyTable($source, $target, $table);
            }
        } finally {
            $target->statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $this->info('SQLite to MySQL copy completed.');

        return self::SUCCESS;
    }

    private function copyTable($source, $target, string $table): void
    {
        $this->line("Copying <info>{$table}</info>...");

        $target->statement("TRUNCATE TABLE `{$table}`");

        $stmt = $source->getPdo()->query('SELECT * FROM "' . str_replace('"', '""', $table) . '"');

        if ($stmt === false) {
            $this->warn("  - skipped (could not read source table): {$table}");

            return;
        }

        $batch = [];
        $copied = 0;
        $batchSize = 500;

        while (($row = $stmt->fetch(\PDO::FETCH_ASSOC)) !== false) {
            $batch[] = $row;

            if (count($batch) >= $batchSize) {
                $target->table($table)->insert($batch);
                $copied += count($batch);
                $batch = [];
            }
        }

        if (! empty($batch)) {
            $target->table($table)->insert($batch);
            $copied += count($batch);
        }

        $this->line("  - rows copied: {$copied}");
    }
}
