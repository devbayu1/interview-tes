<?php

use App\Http\Controllers\API\Controllers\AuthController;
use App\Http\Controllers\API\Controllers\CodeCheckController;
use App\Http\Controllers\API\Controllers\ForgotPasswordController;
use App\Http\Controllers\API\Controllers\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function (Request $request) {
    return response()->json([
        'status' => 200,
        'text' => 'API'
    ]);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('password/email',  ForgotPasswordController::class);
Route::get('password/token/{token}', CodeCheckController::class);
Route::post('password/reset', ResetPasswordController::class);

Route::get('login/{social}', [AuthController::class, 'redirectSocial']);
Route::get('login/{social}/callback', [AuthController::class, 'handleSocial']);
