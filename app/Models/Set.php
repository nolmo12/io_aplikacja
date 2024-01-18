<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids\Hashids;
use Illuminate\Support\Facades\DB;

class Set extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'user_id', 'reference_code'];

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function getOwner()
    {
        $owner = DB::table('sets')
        ->join('users', 'sets.user_id', '=', 'sets.id')
        ->where('sets.user_id', $this->user_id)
        ->value('users.name');
        
        return $owner;
    }

    public function getAllCards()
    {
        $cards = DB::table('sets')
        ->join('cards', 'cards.set_id', '=', 'sets.id')
        ->where('cards.set_id', $this->id)
        ->get();
        
        return $cards;
    }

    public function getAllCardsType(bool $type)
    {
        $cards = DB::table('sets')
        ->join('cards', 'cards.set_id', '=', 'sets.id')
        ->where('cards.set_id', $this->id)
        ->where('cards.is_question', $type)
        ->get();
        
        return $cards;
    }

    public function lobbies()
    {
        return $this->belongsToMany(Lobby::class);
    }

    public static function getSetByReferenceCode(string $code)
    {
        $sets = Set::where('reference_code', $code)->first();
        return $sets;
    }

    public static function getAllUserSets($id)
    {
        $sets = DB::table('sets')
        ->where('user_id', $id)
        ->get();

        return $sets;
    }

    public static function getPopularSets(int $limit = 5)
    {
        $sets = DB::table('sets')
        ->orderBy('used_times')
        ->limit($limit)
        ->get();
        return $sets;
    }

    public static function getRecentSets()
    {
        $sets = DB::table('sets')
        ->orderBy('added_time')
        ->limit(5)
        ->get();
        return $sets;
    }
}
