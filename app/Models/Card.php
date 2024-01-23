<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    public function lobbies()
    {
        return $this->belongsToMany(Lobby::class);
    }

    public function players()
    {
        return $this->belongsToMany(Player::class);
    }
}
