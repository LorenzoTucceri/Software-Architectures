<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\GlobalBotController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::match(['get', 'post'], '/newChat', [GlobalBotController::class, 'newChat']);
Route::match(['get', 'post'], '/oldChat', [GlobalBotController::class, 'oldChat']);
Route::match(['get', 'post'], '/loadChat', [GlobalBotController::class, 'loadChat']);



Route::get('/endpoint', [ApiController::class, 'index']);
Route::post('/endpoint_di_laravel', [ApiController::class, 'store']);

