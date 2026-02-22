<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ImportLog;
use App\Jobs\ProcessCsvImport;

class ScanCsvFolder extends Command
{
    protected $signature = 'csv:scan';
    protected $description = 'Scan storage/app/csv folder and import new CSV files';

    public function handle()
    {
        $folder = storage_path('app/csv');

        if (!is_dir($folder)) {
            $this->error("CSV folder not found: " . $folder);
            return self::FAILURE;
        }

        $files = glob($folder . '/*.csv');

        if (!$files) {
            $this->warn("No CSV files found.");
            return self::SUCCESS;
        }

        $this->info("Files detected: " . count($files));

        foreach ($files as $file) {

            if (!file_exists($file)) {
                $this->error("File missing: " . $file);
                continue;
            }

            $hash = md5_file($file);

            if (!$hash) {
                $this->error("Hash failed: " . basename($file));
                continue;
            }

            $alreadyImported = ImportLog::where('file_hash', $hash)->exists();

            if ($alreadyImported) {
                $this->line("Skipped (already imported): " . basename($file));
                continue;
            }

            (new ProcessCsvImport($file, $hash))->handle();

            $this->info("Imported: " . basename($file));
        }

        $this->info("Scan complete.");

        return self::SUCCESS;
    }
}