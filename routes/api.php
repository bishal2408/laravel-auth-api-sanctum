<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
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

Route::post('/register', [AuthController::class, 'registerUser'])->name('register.user');
Route::post('/login', [AuthController::class, 'loginUser'])->name('login.user');

Route::group(['middleware'=> 'auth:sanctum'], function () {
    Route::post('/logout', [AuthController::class, 'logoutUser'])->name('logout.user');
    Route::get('/user', [AuthController::class, 'getUser'])->name('user');
});
