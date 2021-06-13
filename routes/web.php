<?php

/*
|--------------------------------------------------------------------------
| Marketplace Web Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the MarketplaceSDKServiceProvider within
| a group which contains the "web" middleware group.
|
*/

use Code23\MarketplaceSDK\Http\Controllers\Auth\LoginController;
use Code23\MarketplaceSDK\Http\Controllers\Auth\RegisterController;

Route::group(['middleware' => ['web']], function () {

    Route::get('/login',            [LoginController::class,    'index'])->name('login');
    Route::get('/register',         [RegisterController::class, 'index'])->name('register');
    Route::get('/password/forgot',  [LoginController::class,    'passwordForgot'])->name('password.forgot');
    Route::get('/password/reset',   [LoginController::class,    'passwordReset'])->name('password.reset');

    Route::post('/login',           [LoginController::class,    'login'])->name('login.authenticate');
    Route::post('/register',        [RegisterController::class, 'register'])->name('register.new');
    Route::post('/password/email',  [LoginController::class,    'passwordEmail'])->name('password.email');
    Route::post('/password/update', [LoginController::class,    'passwordUpdate'])->name('password.update');
});
