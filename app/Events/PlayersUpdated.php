<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

class PlayersUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $lobbyId;
    public $players;

    public string $message;
    /**
     * Create a new event instance.
     */
    public function __construct($lobbyId, $players, $message)
    {
        $this->lobbyId = $lobbyId;
        $this->players = $players;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('lobby.' . $this->lobbyId);
    }
}
