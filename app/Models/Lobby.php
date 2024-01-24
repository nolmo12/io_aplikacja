<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lobby extends Model
{
    use HasFactory;
    protected $fillable = ['time_remaining'];
    

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

    public function getAllQuestionCards()
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

    public function getAllAnswerCards()
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

    public function dealCards($cardsRemaining = 5)
    {
        $players = $this->getCurrentPlayers();
        foreach($players as $player)
        {
            if(count($player->cards) < 5)
            {
                for($i = 0; $i < $cardsRemaining; $i++)
                {
                    $cards = $this->getAllAnswerCards();
                    $filtered_cards = $cards->filter(function($card){
                        return  !$this->cards()->wherePivot('card_id', $card->id)->exists();
                    });

                    $random_card = $filtered_cards->random();

                    $player->cards()->attach($random_card);
                    $this->addCard($random_card);
                }  
            }

        }
    }

    public function getBestPlayer()
    {
        $players = $this->getCurrentPlayers();
        
        $bestPlayer = Player::where('lobby_id', $this->id)
        ->orderByDesc('current_points')
        ->first();
        
        return $bestPlayer;
    }

    public function addCard($card)
    {
        $this->cards()->attach($card);
    }

    public function getCurrentQuestionCard()
    {
        return Card::find($this->card_id);
    }

    public function nextRound()
    {
        
    }

    public function getUsedCards()
    {
        return $this->cards;
    }

    public function cards()
    {
        return $this->belongsToMany(Card::class)
        ->withPivot('is_used');
    }

    public function sets()
    {
        return $this->belongsToMany(Set::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function tableCards()
    {
        return $this->hasMany(TableCards::class);
    }

}
