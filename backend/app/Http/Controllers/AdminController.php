<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use App\Models\ImportLog;
use App\Services\DefaulterService;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function students()
    {
        return Student::with('attendances')->get();
    }

    public function defaulters()
    {
        return DefaulterService::getDefaulters();
    }

    public function imports()
    {
        return ImportLog::latest()->get();
    }

    public function runSync()
    {
        \Artisan::call('csv:scan');

        return response()->json([
            'message' => 'CSV Sync Started'
        ]);
    }

    public function uploadCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $file = $request->file('file');

        $name = time() . '_' . $file->getClientOriginalName();

        $file->move(storage_path('app/csv'), $name);

        return response()->json([
            'message' => 'CSV uploaded successfully',
            'file' => $name
        ]);
    }
}