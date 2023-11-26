<?php

use App\Http\Controllers\V1\AnswerController;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\QuestionController;
use App\Http\Controllers\V1\TestController;
use App\Http\Controllers\V1\UserController;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\V1', 'middleware' => 'api'], static function () {
  Route::apiResource('tests', TestController::class);
  Route::apiResource('users', UserController::class);
  Route::apiResource('questions', QuestionController::class);
  Route::apiResource('answers', AnswerController::class);
  Route::post('users/pass-test', [UserController::class, 'passTest']);
});

Route::group([
  'middleware' => 'api',
  'prefix' => 'auth'
], function ($router) {
  Route::post('login', [AuthController::class, 'login']);
  Route::post('logout', [AuthController::class, 'logout']);
  Route::post('refresh', [AuthController::class, 'refresh']);
  Route::post('me', [AuthController::class, 'me']);
});

//Route::post('/auth/register', [UserController::class, 'createUser']);
//Route::post('/auth/login', [UserController::class, 'loginUser']);
