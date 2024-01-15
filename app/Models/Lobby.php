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

    public function countAnswerCards()
    {

    }

    public function countQuestionCards()
    {

    }

    public function getRandomCard()
    {
        $cards = $this->getAllCards();
        $maxValue = count($cards);
        $randomValue = rand(0, $maxValue-1);
        return $cards[$randomValue];
    }

    public function getAllCards()
    {
        $this->load('sets.cards');

        return $this->sets->flatMap(function ($set) {
            return $set->cards;
        });
    }

    public function getUsedCards()
    {
        return $this->cards;
    }

    public function cards()
    {
        return $this->belongsToMany(Card::class);
    }
    public function sets()
    {
        return $this->belongsToMany(Set::class);
    }

}
