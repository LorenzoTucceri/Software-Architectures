<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;

use App\Http\Controllers\MicroserviceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'auth/login')->name("login");
Route::post('/loginPost', LoginController::class)->name("loginPost");
Route::get('/chat', HomeController::class)->name("welcome")->middleware("auth");
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/addMicroservice', [MicroserviceController::class, 'store'])->name("addMicroservice");
Route::post('/microservices/{id}', [MicroserviceController::class, 'destroy'])->name('microservices.delete');
