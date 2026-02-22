<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\StudentDashboardController;
use App\Http\Controllers\Api\CsvImportController;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\AdminAnalyticsController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\AdminController;


/*
|--------------------------------------------------------------------------
| Student APIs
|--------------------------------------------------------------------------
*/

Route::get('/student/{bt_id}', [StudentDashboardController::class, 'show']);

Route::get('/students', [StudentController::class, 'index']);
Route::post('/students', [StudentController::class, 'store']);
Route::get('/students/{id}', [StudentController::class, 'show']);


/*
|--------------------------------------------------------------------------
| CSV Import
|--------------------------------------------------------------------------
*/

Route::get('/import-csv/{filename}', [CsvImportController::class, 'import']);


/*
|--------------------------------------------------------------------------
| Admin Auth
|--------------------------------------------------------------------------
*/

Route::post('/admin/login', [AdminAuthController::class, 'login']);


/*
|--------------------------------------------------------------------------
| Admin Panel
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {

    Route::post('/upload', [AdminController::class, 'uploadCsv']);

    Route::get('/students', [AdminController::class, 'students']);

    Route::get('/imports', [AdminController::class, 'imports']);

    Route::post('/sync', [AdminController::class, 'runSync']);

});


/*
|--------------------------------------------------------------------------
| Analytics
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {

    Route::get('/overview', [AdminAnalyticsController::class, 'collegeOverview']);

    Route::get('/branch-analytics', [AdminAnalyticsController::class, 'branchStats']);

    Route::get('/subject-analytics', [AdminAnalyticsController::class, 'subjectStats']);

    Route::get('/defaulters', [AdminAnalyticsController::class, 'defaulters']);

});


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/admin/analytics', [AnalyticsController::class, 'dashboard']);


/*
|--------------------------------------------------------------------------
| Test
|--------------------------------------------------------------------------
*/

Route::get('/admin/test', function () {
    return "Admin API working";
});