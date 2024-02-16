<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;

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


Route::middleware('guest')->group(function () {
    Route::view('/', 'auth/login')->name('login');
    Route::post('/loginPost', UserController::class)->name("loginPost");
    Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [UserController::class, 'register'])->name('registerPost');
});

Route::middleware('auth')->group(function () {
    Route::get('/chat', HomeController::class)->name("welcome");
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::post('/addMicroservice', [MicroserviceController::class, 'store'])->name("addMicroservice");
    Route::post('/microservices/{id}', [MicroserviceController::class, 'destroy'])->name('microservices.delete');
    Route::get('chat/{id}', [HomeController::class, 'getMessagesByChatId'])->name('chat.show');
    Route::post('/update-user', [UserController::class, 'updateUser'])->name('updateProfile');
    
});


