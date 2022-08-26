<?php

namespace App\Http\Livewire;

use Illuminate\Notifications\DatabaseNotification;
use Livewire\Component;

class NotifyComponent extends Component
{
    public $countNotifications, $unreadNotifications;

    public $active = false;

    public function getListeners()
    {
        return [
            'echo:chat,MessageSent' => 'updateNotifies',
        ];
    }
    public function render()
    {
        $this->active = true;
        $this->countNotifications = count(auth()->user()->unreadNotifications);
        $this->unreadNotifications = auth()->user()->unreadNotifications;
        return view('livewire.notify-component');
    }

    /* public function markAsRead(DatabaseNotification $notification)
    {
        $this->active = false;
        $notification->markAsRead();
        $this->reset(['countNotifications', 'unreadNotifications', 'active']);
    }
 */
    public function updateNotifies()
    {
        $this->reset(['countNotifications', 'unreadNotifications', 'active']);
    }
}
