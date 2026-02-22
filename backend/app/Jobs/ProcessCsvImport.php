<?php

namespace App\Jobs;

use App\Models\ImportLog;
use App\Http\Controllers\Api\CsvImportController;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCsvImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;
    protected $hash;

    public function __construct($file, $hash)
    {
        $this->file = $file;
        $this->hash = $hash;
    }

    public function handle()
    {
        $filename = basename($this->file);

        $controller = new CsvImportController();

        $controller->import($filename);

        ImportLog::create([
            'file_name' => $filename,
            'file_hash' => $this->hash,
            'rows_processed' => 0
        ]);
    }
}