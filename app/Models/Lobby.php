<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lobby extends Model
{
    use HasFactory;

    public function countCurrentPlayers()
    {
        $playerCount = DB::table('players')->where('lobby_id', $this->id)->count();
        return $playerCount;
    }

    public function getCurrentPlayers()
    {
        $players = DB::table('players')->where('lobby_id', $this->id)->get();
        return $players;
    }

    public function getOwner()
    {
        $owner = DB::table('lobbies')
        ->join('users', 'lobbies.user_id', '=', 'users.id')
        ->where('lobbies.user_id', $this->user_id)
        ->value('users.name');
        
        return $owner;
    }
}
