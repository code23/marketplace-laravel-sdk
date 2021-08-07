<?php

/*
|--------------------------------------------------------------------------
| Marketplace Web Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the MarketplaceLaravelSDKServiceProvider within
| a group which contains the "web" middleware group.
|
*/

use Code23\MarketplaceLaravelSDK\Http\Controllers\Auth\LoginController;
use Code23\MarketplaceLaravelSDK\Http\Controllers\Auth\RegisterController;

Route::group(['middleware' => ['web']], function () {

    Route::get('/login',                    [LoginController::class,    'index'])->name('login');
    Route::get('/logout',                   [LoginController::class,    'logout'])->name('logout');
    Route::get('/register',                 [RegisterController::class, 'index'])->name('register');
    Route::get('/password/forgot',          [LoginController::class,    'passwordForgot'])->name('password.forgot');
    Route::get('/password/reset',           [LoginController::class,    'passwordReset'])->name('password.reset');
    Route::get('/two-factor-auth/{state}',  [LoginController::class,    'twoFactorAuthentication'])->name('two-factor.authentication');
    Route::get('/two-factor-details',       [LoginController::class,    'twoFactorDetails'])->name('two-factor.confirmation');

    Route::post('/login',           [LoginController::class,    'login'])->name('login.authenticate');
    Route::post('/register',        [RegisterController::class, 'register'])->name('register.new');
    Route::post('/password/email',  [LoginController::class,    'passwordEmail'])->name('password.email');
    Route::post('/password/update', [LoginController::class,    'passwordUpdate'])->name('password.update');
    Route::post('/two-factor-auth', [LoginController::class,    'twoFactorValidation'])->name('two-factor.validation');
});
