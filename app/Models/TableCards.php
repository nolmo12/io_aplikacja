<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableCards extends Model
{
    use HasFactory;
    protected $fillable = ['lobby_id', 'player_id', 'card_id'];
    
    public function lobby()
    {
        return $this->belongsTo(Lobby::class);
    }

    /**
     * Get the card associated with the table card.
     */
    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Get the player who placed the card on the table (if applicable).
     */
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
