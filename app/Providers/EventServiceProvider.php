<?php

namespace App\Providers;

use App\Events\RequestEvent;
use App\Listeners\RequestListener;
use App\Events\RHRequestEvent;
use App\Events\UserEvent;
use App\Listeners\RHRequestListener;
use App\Listeners\UserListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        RequestEvent::class => [
            RequestListener::class,
        ],
        RHRequestEvent::class => [
            RHRequestListener::class,
        ],
        UserEvent::class => [
            UserListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
