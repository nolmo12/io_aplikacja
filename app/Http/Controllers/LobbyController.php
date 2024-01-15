<?php

namespace App\Http\Controllers;

use App\Models\Lobby;
use App\Models\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LobbyController extends Controller
{
    public function index()
    {
        return view("lobbies.index", [
            'lobbies' => Lobby::all()
        ]);
    }
    //Show single tournament
    public function show($id)
    {
        return view("lobbies.show", [
            'lobby' => Lobby::find($id)
        ]);
    }
    public function create()
    {
        return view("lobbies.create");
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'max_players' => 'required|integer',
            'max_rounds' => 'required|integer',
            'sets' => 'required|string',
        ]);

        $lobby = new Lobby;
        $lobby->name = $request->name;
        $lobby->max_players = $request->max_players;
        $lobby->max_rounds = $request->max_rounds;
        $lobby->card_id = 0;
        $lobby->user_id = Auth::user()->id;
        $lobby->save();
        $reference_codes = explode(",", $request->sets);

        foreach($reference_codes as &$reference_code)
        {
            $reference_code = str_replace(" ", "", $reference_code);
        }

        foreach($reference_codes as &$reference_code)
        {
            $setId = Set::getSetByReferenceCode($reference_code)->id;

            $set = Set::find($setId);

            $lobby->sets()->attach($set);
        }

        $lobby->refresh();

        $card = $lobby->getRandomCard();

        $lobby->cards()->attach($card);

        $lobby->card_id = $card->id;

        $lobby->save();

        return redirect('lobbies/create')->with('status', 'Set: '.$lobby->name.' has been created!');
    }
}
