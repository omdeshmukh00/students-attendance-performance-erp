<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ImportLog;
use App\Jobs\ProcessCsvImport;

class ScanCsvFolder extends Command
{
    protected $signature = 'csv:scan';
    protected $description = 'Scan CSV folder and import new files';

    public function handle()
    {
        $files = glob(storage_path('app/csv/*.csv'));

        $this->info("Files detected: " . count($files));

        foreach ($files as $file) {

            $hash = md5_file($file);

            $exists = ImportLog::where('file_hash', $hash)->exists();

            if ($exists) {
                $this->line("Skipped (already imported): " . basename($file));
                continue;
            }

            ProcessCsvImport::dispatch($file, $hash);

            $this->info("Queued import: " . basename($file));
        }

        return self::SUCCESS;
    }
}