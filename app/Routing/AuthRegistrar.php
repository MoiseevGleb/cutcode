<?php

namespace App\Routing;

use App\Contracts\RouteRegistrar;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\TestController;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\Facades\Route;

class AuthRegistrar implements RouteRegistrar
{
    public function map(Registrar $registrar): void
    {
        Route::middleware('web')->group(function () {

            Route::controller(SignInController::class)->group(function () {

                Route::get('/login', 'page')
                    ->name('login');

                Route::post('/login', 'handle')
                    ->middleware(['throttle:auth', 'guest'])
                    ->name('signIn');

                Route::delete('/logout', 'logout')
                    ->middleware('auth')
                    ->name('logout');

            });

            Route::controller(SignUpController::class)->group(function () {

                Route::get('/sign-up', 'page')
                    ->name('signUp');

                Route::post('/sign-up', 'handle')
                    ->middleware(['throttle:auth', 'guest'])
                    ->name('store');

            });

            Route::controller(ForgotPasswordController::class)->group(function () {

                Route::get('/forgot-password', 'page')
                    ->middleware('guest')
                    ->name('password.request');

                Route::post('/forgot-password', 'handle')
                    ->middleware('guest')
                    ->name('password.email');

            });

            Route::controller(ResetPasswordController::class)->group(function () {

                Route::get('/reset-password/{token}', 'page')
                    ->middleware('guest')
                    ->name('password.reset');

                Route::post('/reset-password', 'handle')
                    ->middleware('guest')
                    ->name('password.update');

            });

            Route::controller(SocialAuthController::class)->group(function () {

                Route::get('/auth/socialite/{driver}', 'redirect')
                    ->name('socialite.redirect');

                Route::get('/auth/socialite/{driver}/callback', 'callback')
                    ->name('socialite.callback');

            });

        });
    }
}
