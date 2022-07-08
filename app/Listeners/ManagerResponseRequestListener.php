<?php

namespace App\Listeners;

use App\Events\ManagerResponseRequestEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ManagerResponseRequestListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Providers\ManagerResponseRequestEvent  $event
     * @return void
     */
    public function handle(ManagerResponseRequestEvent $event)
    {
        //
    }
}
