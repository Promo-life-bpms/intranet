<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ManagerResponseRequestEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $type;
    public $receptor;
    public $emisor;
    public $status;
    public $emisor_name;
    public function __construct($type, $receptor, $emisor, $emisor_name, $status)
    {
        $this->type = $type;
        $this->status = $status;
        $this->receptor = $receptor;
        $this->emisor = $emisor;
        $this->emisor_name = $emisor_name;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['updateManagerRequest'];
    }
}
