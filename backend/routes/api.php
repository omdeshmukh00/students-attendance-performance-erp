<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\StudentDashboardController;
use App\Http\Controllers\Api\CsvImportController;

use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\AdminAnalyticsController;

use App\Http\Controllers\AdminController;



/*
|--------------------------------------------------------------------------
| STUDENT APIs
|--------------------------------------------------------------------------
*/

Route::get('/students', [StudentController::class, 'index']);

Route::post('/students', [StudentController::class, 'store']);

Route::get('/students/{id}', [StudentController::class, 'show']);

/* Student Dashboard */
Route::get('/student/{bt_id}', [StudentDashboardController::class, 'show']);



/*
|--------------------------------------------------------------------------
| CSV IMPORT (Manual Trigger)
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

    /* Upload CSV */
    Route::post('/upload', [AdminController::class, 'uploadCsv']);

    /* Run Auto Sync */
    Route::post('/sync', [AdminController::class, 'runSync']);

    /* Students List */
    Route::get('/students', [AdminController::class, 'students']);

    /* Import Logs */
    Route::get('/imports', [AdminController::class, 'imports']);


    /*
    |--------------------------------------------------------------------------
    | ADMIN ANALYTICS
    |--------------------------------------------------------------------------
    */

    Route::get('/overview', [AdminAnalyticsController::class, 'collegeOverview']);

    Route::get('/branch-analytics', [AdminAnalyticsController::class, 'branchStats']);

    Route::get('/subject-analytics', [AdminAnalyticsController::class, 'subjectStats']);

    Route::get('/defaulters', [AdminAnalyticsController::class, 'defaulters']);

});


/*
|--------------------------------------------------------------------------
| TEST ROUTE
|--------------------------------------------------------------------------
*/

Route::get('/admin/test', function () {
    return response()->json([
        "status" => "Admin API working"
    ]);
});