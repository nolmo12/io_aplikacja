<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

class SetUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $lobbyId;

    public $lobby_sets;
    public $setMessage;

    /**
     * Create a new event instance.
     *
     * @param int $lobbyId
     * @param mixed $lobby_sets
     * @param string $setMessage
     */
    public function __construct($lobbyId, $lobby_sets, $setMessage)
    {
        $this->lobbyId = $lobbyId;
        $this->lobby_sets = $lobby_sets;
        $this->setMessage = $setMessage;
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