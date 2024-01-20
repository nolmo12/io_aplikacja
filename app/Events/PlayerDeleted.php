<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayerDeleted implements ShouldBroadcast 
{
    use Dispatchable, SerializesModels;

    public $lobby_id;
    public $user_id;

    /**
     * Create a new event instance.
     */
    public function __construct($user_id, $lobby_id)
    {
        $this->user_id = $user_id;
        $this->lobby_id = $lobby_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('lobby.' . $this->lobby_id);
    }
}
