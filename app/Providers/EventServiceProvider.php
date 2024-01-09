<?php

namespace App\Providers;

use App\Events\AfterSessionRegenerated;
use App\Listeners\SendEmailToNewUserListener;
use Domain\Cart\CartManager;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Domain\Catalog\Observers\BrandObserver;
use Domain\Catalog\Observers\CategoryObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
        Event::listen(AfterSessionRegenerated::class, function (AfterSessionRegenerated $event) {
             $this->app->make(CartManager::class)->updateStorageId($event->old, $event->current);
        });
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
