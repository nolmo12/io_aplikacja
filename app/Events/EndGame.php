<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EndGame implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $lobby_id;
    public $user_name;
    public $points;
    /**
     * Create a new event instance.
     */
    public function __construct($lobby_id,$user_name, $points)
    {
        $this->lobby_id =$lobby_id;
        $this->user_name = $user_name;
        $this->points = $points;
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