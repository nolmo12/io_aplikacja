<?php

namespace App\Http\Controllers;

use App\Models\Lobby;
use App\Models\Player;
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
    public function create($id)
    {
        return view("lobbies.create",[
            'lobby' => Lobby::find($id)
        ]);
    }

    public function create_temp()
    {
        $lobby = new Lobby;
        if(Lobby::where('name', Auth::user()->name.'s Lobby')->first())
            return redirect()->route('lobby', ['id' => Lobby::where('name', Auth::user()->name.'s Lobby')->first()->id]);
        $lobby->name = Auth::user()->name.'s Lobby';
        $lobby->user_id = Auth::user()->id;
        $lobby->round_timer = 1;
        $lobby->card_id = 0;
        $lobby->save();

        $player = new Player;

        $player->user_id = Auth::user()->id;
        $player->lobby_id = $lobby->id;
        $player->current_points = 0;
        $player->is_judge = False;
        
        $player->save();

        return redirect()->route('lobby', ['id' => $lobby->id]);
    }

    public function store(Request $request, int $id)
    {
        $lobby = Lobby::find($id);

        $playerCount = $lobby->countCurrentPlayers();

        $minAnswerCards = 5 * $playerCount * $request->max_rounds;
        $minQuestionCards = $playerCount * $request->max_rounds;

        if($playerCount < 3)
            return redirect('lobby/'.$lobby->id)->with('status', 'Za mało graczy by rozpocząć rozgrywkę');

        if(count($lobby->getAllQuestionCards()) > $minQuestionCards)
            return redirect('lobby/'.$lobby->id)->with('status', 'Za mało kart pytań. Brakuję: '.$minQuestionCards - count($lobby->getAllQuestionCards()));

        if(count($lobby->getAllQuestionCards()) > $minAnswerCards)
            return redirect('lobby/'.$lobby->id)->with('status', 'Za mało kart odpowiadających Brakuję: '.$minAnswerCards - count($lobby->getAllAnswerCards()));

        $lobby->name = $request->lobby_name;
        $lobby->max_players = $request->max_players;
        $lobby->max_rounds = $request->max_rounds;
        $lobby->card_id = 0;
        $lobby->user_id = Auth::user()->id;
        $lobby->save();

        return redirect('lobby/'.$lobby->id)->with('status', 'Set: '.$lobby->name.' has been created!');
    }

    public function update(Request $request, $id)
    {
        // Assuming you have a Lobby model
        $lobby = Lobby::find($id);

        if (!$lobby) {
            return response()->json(['error' => 'Lobby not found'], 404);
        }

        // Update the round timer value based on the input from the AJAX request
        $lobby->round_timer = $request->input('turn_time') * 1000;
        $lobby->save();

        // You can also refresh the lobby and send updated data back to the client if needed
        $lobby->refresh();

        return response()->json(['success' => true, 'updated_timer' => $lobby->round_timer]);
    }

    public function removeSet($lobbyId, $setId)
    {
        $lobby = Lobby::find($lobbyId);
        $set = Set::find($setId);

        if ($set)
        {
            $lobby->sets()->detach($set);
            return response()->json(['success' => true], 200);
        } else {
            return response()->json(['error' => 'Set not found'], 404);
        }
    }

}
