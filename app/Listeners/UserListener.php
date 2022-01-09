<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\RequestNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class UserListener
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $id = $event->request->employee_id;
        User::all()->where('id', $id)->each(function (User $user) use ($event) {
            Notification::send($user, new RequestNotification($event->request));
        });
    }
}
