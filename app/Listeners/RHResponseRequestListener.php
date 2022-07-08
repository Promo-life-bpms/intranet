<?php

namespace App\Listeners;

use App\Events\RHResponseRequestEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RHResponseRequestListener
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
     * @param  \App\Providers\RHResponseRequestEvent  $event
     * @return void
     */
    public function handle(RHResponseRequestEvent $event)
    {
        //
    }
}
