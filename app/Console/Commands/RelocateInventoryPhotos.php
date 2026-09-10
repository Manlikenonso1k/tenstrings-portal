<?php

namespace App\Console\Commands;

use App\Models\InventoryItem;
use App\Models\InventoryRoom;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Photos uploaded before FILAMENT_FILESYSTEM_DISK was corrected landed in
 * storage/app/private, which the web server never serves. This moves them onto
 * the public disk so the existing rows keep working.
 */
class RelocateInventoryPhotos extends Command
{
    protected $signature = 'inventory:relocate-photos {--from=local} {--to=public} {--dry-run}';

    protected $description = 'Move inventory photos from the private disk to the public disk';

    public function handle(): int
    {
        $from = (string) $this->option('from');
        $to = (string) $this->option('to');
        $dryRun = (bool) $this->option('dry-run');

        $moved = 0;
        $missing = 0;

        foreach ([[InventoryItem::class, 'photo_path'], [InventoryRoom::class, 'image']] as [$model, $column]) {
            $records = $model::query()
                ->withoutGlobalScope('branch')
                ->whereNotNull($column)
                ->get();

            foreach ($records as $record) {
                $path = (string) $record->{$column};

                if ($path === '' || Storage::disk($to)->exists($path)) {
                    continue;
                }

                if (! Storage::disk($from)->exists($path)) {
                    $this->warn("Missing on {$from}: {$path}");
                    $missing++;

                    continue;
                }

                $this->line(($dryRun ? '[dry-run] ' : '') . "{$from}:{$path} -> {$to}:{$path}");

                if (! $dryRun) {
                    Storage::disk($to)->put($path, Storage::disk($from)->get($path));
                }

                $moved++;
            }
        }

        $this->info(($dryRun ? 'Would move ' : 'Moved ') . $moved . ' file(s); ' . $missing . ' missing.');

        return self::SUCCESS;
    }
}
