<?php

namespace App\Listeners;

use App\Notifications\NewUserNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailToNewUserListener
{
    public function handle(Registered $event): void
    {
        $event->user->notify(new NewUserNotification());
    }
}
