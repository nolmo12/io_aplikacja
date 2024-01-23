<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdatePoints implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $lobby_id;
    public $player_id;
    public $card_id;
    public $points;

    /**
     * Create a new event instance.
     */
    public function __construct($lobby_id, $player_id, $points)
    {
        $this->lobby_id = $lobby_id;
        $this->player_id = $player_id;
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
