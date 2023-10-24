<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group([
    'controller' => AuthController::class,
], function () {
    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'signIn')
        ->middleware(['throttle:auth', 'guest'])
        ->name('signIn');

    Route::get('/sign-up', 'signUp')->name('signUp');
    Route::post('/sign-up', 'store')
        ->middleware(['throttle:auth', 'guest'])
        ->name('store');

    Route::delete('/logout', 'logout')
        ->middleware('auth')
        ->name('logout');

    Route::get('/forgot-password', 'forgot')->middleware('guest')->name('password.request');
    Route::post('/forgot-password', 'forgotPassword')->middleware('guest')->name('password.email');
    Route::get('/reset-password/{token}', 'reset')->middleware('guest')->name('password.reset');
    Route::post('/reset-password', 'update')->middleware('guest')->name('password.update');

    Route::get('/auth/socialite/github', 'github')->name('socialite.github');
    Route::get('/auth/socialite/github/callback', 'githubCallback')->name('socialite.github.callback');
});

Route::get('/', HomeController::class)->name('home');


