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
        return Player::where('lobby_id', $this->id)->get();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getRandomQuestionCard()
    {
        $questionCards = $this->getAllQuestionCards();
        $maxValue = count($questionCards);
        if ($maxValue > 0)
        {
            $randomValue = rand(0, $maxValue - 1);
            return $questionCards[$randomValue];
        }
        return 0;
    }

    public function getAllCards()
    {
        $this->load('sets.cards');

        return $this->sets->flatMap(function ($set) {
            return $set->cards;
        });
    }

    private function getAllQuestionCards()
    {
    $this->load('sets.cards');

    return $this->sets->flatMap(function ($set) {
        return $set->cards->filter(function ($card) {
            return $card->is_question;
        });
    });
    }

    public function getRandomAnswerCard()
    {
        $answerCards = $this->getAllAnswerCards();

        $maxValue = count($answerCards);

        if ($maxValue > 0) {
            $randomValue = rand(0, $maxValue - 1);
            return $answerCards[$randomValue];
        }

        return 0;
    }

    private function getAllAnswerCards()
    {
        $this->load('sets.cards');

        return $this->sets->flatMap(function ($set) {
            return $set->cards->filter(function ($card) {
                return !$card->is_question;
            });
        });
    }

    public function hasPassword():bool
    {
        if($this->password != '')
            return True;
        return False;
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
