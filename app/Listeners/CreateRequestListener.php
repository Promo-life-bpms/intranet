<?php

namespace App\Listeners;

use App\Events\CreateRequestEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateRequestListener
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
     * @param  \App\Events\CreateRequestEvent  $event
     * @return void
     */
    public function handle(CreateRequestEvent $event)
    {
        //
    }
}
