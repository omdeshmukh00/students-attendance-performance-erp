<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Api\CsvImportController;

class ImportAllCsv extends Command
{
    protected $signature = 'csv:import-all';
    protected $description = 'Import all CSV files from storage/app/csv';

    public function handle()
{
    $path = storage_path('app/csv');

    if (!File::exists($path)) {
        $this->error("CSV directory does not exist.");
        return;
    }

    $files = File::files($path);

    if (empty($files)) {
        $this->info("No CSV files found.");
        return;
    }

    $controller = new CsvImportController();

    foreach ($files as $file) {

        $filename = $file->getFilename();

        // 🔹 Check if file already imported
        $alreadyImported = \DB::table('imported_files')
            ->where('filename', $filename)
            ->exists();

        if ($alreadyImported) {
            $this->info("Skipping already imported file: " . $filename);
            continue;
        }

        $this->info("Importing: " . $filename);

        try {

            $controller->import($filename);

            // 🔹 Mark file as imported
            \DB::table('imported_files')->insert([
                'filename' => $filename,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->info("Imported successfully: " . $filename);

        } catch (\Exception $e) {

            $this->error("Failed: " . $filename);
            $this->error($e->getMessage());
        }
    }

    $this->info("All CSV files processed.");
}
}