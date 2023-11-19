<?php

namespace Domain\Auth\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        //
    ];

    public function register(): void
    {
        $this->app->register(ActionsServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
