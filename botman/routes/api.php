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

Route::match(['get', 'post'], '/botman', GlobalBotController::class);



Route::get('/endpoint', [ApiController::class, 'index']);
Route::post('/endpoint_di_laravel', [ApiController::class, 'store']);