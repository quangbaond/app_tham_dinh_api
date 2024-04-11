<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([ 'middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);
    // Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

});

Route::post('/upload-cccd', [UserController::class, 'uploadCccd'])->middleware('auth:api');
Route::post('/upload-blx', [UserController::class, 'uploadBLX'])->middleware('auth:api');
Route::post('/update-finance', [UserController::class, 'updateFinance'])->middleware('auth:api');
Route::post('/update-user', [UserController::class, 'updateUser'])->middleware('auth:api');
Route::post('/update-tai-san', [UserController::class, 'updateTaiSan'])->middleware('auth:api');
Route::get('/get-period', [SettingController::class, 'getPeriod']);
Route::post(('/create-loan-amount'), [UserController::class, 'createLoanAmount'])->middleware('auth:api');
Route::get('/get-user-load-amount/{id}', [UserController::class, 'getUserLoanAmount'])->middleware('auth:api');
