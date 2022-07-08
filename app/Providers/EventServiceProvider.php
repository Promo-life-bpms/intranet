<?php

namespace App\Providers;

use App\Events\CreateRequestEvent;
use App\Events\UserEvent;
use App\Listeners\CreateRequestListener;
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
        CreateRequestEvent::class=>[
            CreateRequestListener::class
        ],
        ManagerResponseRequestEvent::class=>[
            ManagerResponseRequestListener::class
        ],
        RHResponseRequestEvent::class=>[
            RHResponseRequestListener::class
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
