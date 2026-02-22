<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\CsvImportController;
use App\Http\Controllers\Api\StudentDashboardController;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\AdminAnalyticsController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/student/{bt_id}', [StudentDashboardController::class, 'show']);
Route::get('/students', [StudentController::class, 'index']);
Route::get('/students/{id}', [StudentController::class, 'show']);

/*
|--------------------------------------------------------------------------
| CSV IMPORT
|--------------------------------------------------------------------------
*/

Route::get('/import-csv/{filename}', [CsvImportController::class, 'import']);

/*
|--------------------------------------------------------------------------
| ADMIN AUTH
|--------------------------------------------------------------------------
*/

Route::post('/admin/login', [AdminAuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| ADMIN PANEL
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {

    Route::get('/overview', [AdminAnalyticsController::class, 'collegeOverview']);

    Route::get('/branch-analytics', [AdminAnalyticsController::class, 'branchStats']);

    Route::get('/subject-analytics', [AdminAnalyticsController::class, 'subjectStats']);

    Route::get('/defaulters', [AdminAnalyticsController::class, 'defaulters']);

    Route::post('/upload', [AdminController::class, 'uploadCsv']);

    Route::get('/imports', [AdminController::class, 'imports']);

    Route::post('/sync', [AdminController::class, 'runSync']);
});