<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\UserController;
use App\Http\Controllers\api\v1\ProjectController;
use App\Http\Controllers\api\v1\TimeSheetController;
use App\Http\Controllers\api\v1\RecordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::resource('users', UserController::class)->except([
//     'create', 'edit',
// ]);

// Route::get('/users/all',[UserController::class,'index']);
Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'login'])->name('login');


Route::middleware('auth:api')->group(function () {
    Route::post('/logout',[UserController::class,'logout'])->name('logout');
    Route::post('/user',[UserController::class,'store']);
    Route::get('/user/all',[UserController::class,'index']);
    Route::get('/user/{id}',[UserController::class,'show']);
    Route::post('/user/update/{id}',[UserController::class,'update']);
    Route::delete('/user/delete/{id}',[UserController::class,'destroy']);

    Route::post('/project',[ProjectController::class,'store']);
    Route::get('/project/all',[ProjectController::class,'index']);
    Route::get('/project/{id}',[ProjectController::class,'show']);
    Route::post('/project/update/{id}',[ProjectController::class,'update']);
    Route::delete('/project/delete/{id}',[ProjectController::class,'destroy']);

    Route::post('/timesheet',[TimeSheetController::class,'store']);
    Route::get('/timesheet/all',[TimeSheetController::class,'index']);
    Route::get('/timesheet/{id}',[TimeSheetController::class,'show']);
    Route::post('/timesheet/update/{id}',[TimeSheetController::class,'update']);
    Route::delete('/timesheet/delete/{id}',[TimeSheetController::class,'destroy']);

    Route::get('/records',[RecordController::class,'index']);
});