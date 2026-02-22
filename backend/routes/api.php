<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\CsvImportController;
use App\Http\Controllers\Api\StudentDashboardController;
use App\Http\Controllers\AdminController;

Route::prefix('admin')->group(function () {

    Route::get('/students', [AdminController::class, 'students']);

    Route::get('/defaulters', [AdminController::class, 'defaulters']);

    Route::get('/imports', [AdminController::class, 'imports']);

    Route::post('/sync', [AdminController::class, 'runSync']);
});
Route::get('/students', [StudentController::class, 'index']);
Route::post('/students', [StudentController::class, 'store']);
Route::get('/students/{id}', [StudentController::class, 'show']);

Route::get('/import-csv/{filename}', [CsvImportController::class, 'import']);
Route::get('/student/{bt_id}', 
    [StudentDashboardController::class, 'show']
);

