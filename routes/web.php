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

use Code23\MarketplaceLaravelSDK\Http\Controllers\Auth\AuthController;
use Code23\MarketplaceLaravelSDK\Http\Controllers\Auth\RegisterController;

Route::group(['as' => 'mls.', 'middleware' => ['web']], function () {

    Route::get('/login',                    [AuthController::class,    'index'])->name('login');
    Route::get('/logout',                   [AuthController::class,    'logout'])->name('logout');
    Route::get('/register',                 [RegisterController::class, 'index'])->name('register');
    Route::get('/password/forgot',          [AuthController::class,    'passwordForgot'])->name('password.forgot');
    Route::get('/password/reset',           [AuthController::class,    'passwordReset'])->name('password.reset');
    Route::get('/two-factor-auth/{state}',  [AuthController::class,    'twoFactorAuthentication'])->name('two-factor.authentication');
    Route::get('/two-factor-details',       [AuthController::class,    'twoFactorDetails'])->name('two-factor.confirmation');

    Route::post('/login',           [AuthController::class,    'login'])->name('login.authenticate');
    Route::post('/register',        [RegisterController::class, 'register'])->name('register.new');
    Route::post('/password/email',  [AuthController::class,    'passwordEmail'])->name('password.email');
    Route::post('/password/update', [AuthController::class,    'passwordUpdate'])->name('password.update');
    Route::post('/two-factor-auth', [AuthController::class,    'twoFactorValidation'])->name('two-factor.validation');
});
