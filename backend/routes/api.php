<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\CsvImportController;
use App\Http\Controllers\Api\StudentDashboardController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::post('/admin/login', [AdminAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/admin/upload-csv', [CsvImportController::class, 'uploadAndImport']);

    // Optional: protect manual import if you still want it
    // Route::get('/import-csv/{filename}', [CsvImportController::class, 'import']);
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

Route::get('/students', [StudentController::class, 'index']);
Route::post('/students', [StudentController::class, 'store']);
Route::get('/students/{id}', [StudentController::class, 'show']);

Route::get('/student/{bt_id}', [StudentDashboardController::class, 'show']);