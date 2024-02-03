<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_judge',
    ];

    public function playRandomCard()
    {
        $cards = $this->cards;
        return $cards->isNotEmpty() ? $cards->random() : null;
    }

    public function countCards(): int
    {
        $count = $this->cards()->count();
        return $count;
    }

    public function remove($card)
    {
        $this->cards()->detach($card->id);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lobby()
    {
        return $this->belongsTo(Lobby::class);
    }

    public function cards()
    {
        return $this->belongsToMany(Card::class);
    }
}
