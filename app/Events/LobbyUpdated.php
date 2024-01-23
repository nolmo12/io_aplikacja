<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LobbyUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $lobby_id;
    public $lobby_name;
    public $round_timer;
    public $max_rounds;
    public $max_players;
    public function __construct($lobby_id, $lobby_name, $round_timer, $max_rounds, $max_players)
    {
        $this->lobby_id = $lobby_id;
        $this->lobby_name = $lobby_name;
        $this->round_timer = $round_timer;
        $this->max_rounds = $max_rounds;
        $this->max_players = $max_players;
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
