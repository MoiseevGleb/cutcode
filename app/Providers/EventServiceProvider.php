<?php

namespace App\Providers;

use App\Listeners\SendEmailToNewUserListener;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Domain\Catalog\Observers\BrandObserver;
use Domain\Catalog\Observers\CategoryObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $observers = [
        Category::class => [
            CategoryObserver::class
        ],

        Brand::class => [
            BrandObserver::class
        ],
    ];

    protected $listen = [
        Registered::class => [
            SendEmailToNewUserListener::class,
            //SendEmailVerificationNotification::class,
        ],
    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
