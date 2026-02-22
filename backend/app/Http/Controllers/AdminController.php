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
}