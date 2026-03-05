<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

Route::middleware('student.auth')->group(function () {

    Route::get('/dashboard/{id}', function ($id) {
        return response()->json([
            "message" => "Welcome to dashboard"
        ]);
    });

});

Route::post('/login',[AuthController::class, 'login']);
Route::post('/logout',[AuthController::class, 'logout']);
Route::middleware(['auth'])->group(function(){

    Route::get('/dashboard',function(){
        return view('dashboard');
    });

});

Route::get('/', function () {
    return view('welcome');
});
