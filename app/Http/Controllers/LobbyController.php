<?php

namespace App\Http\Controllers;

use App\Events\PlayerDeleted;
use App\Events\PlayersUpdated;
use App\Events\SetUpdated;
use App\Models\Lobby;
use App\Models\Player;
use App\Models\Set;
use App\Models\User;
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

        $player->user()->associate(Auth::user());
        $player->lobby()->associate($lobby);
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

        if(count($lobby->getAllQuestionCards()) < $minQuestionCards)
            return redirect('lobby/'.$lobby->id)->with('status', 'Za mało kart pytań. Brakuję: '.$minQuestionCards - count($lobby->getAllQuestionCards()));

        if(count($lobby->getAllAnswerCards()) < $minAnswerCards)
            return redirect('lobby/'.$lobby->id)->with('status', 'Za mało kart odpowiedzi Brakuję: '.$minAnswerCards - count($lobby->getAllAnswerCards()));

        $lobby->name = $request->lobby_name;
        $lobby->max_players = $request->max_players;
        $lobby->max_rounds = $request->max_rounds;
        $lobby->card_id = $lobby->getRandomQuestionCard();
        $lobby->user_id = Auth::user()->id;
        $lobby->save();

        return redirect('lobby/'.$lobby->id)->with('status', 'Lobby: '.$lobby->name.' zostało rozpoczęte!');
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
            broadcast(new SetUpdated($lobby->id, $lobby->sets,'Set removed from lobby succesfully.'));
        } else {
            return response()->json(['error' => 'Set not found'], 404);
        }
    }

    public function removePlayer($lobbyId, $playerId)
    {
        $lobby = Lobby::find($lobbyId);
        $player = Player::find($playerId);

        if ($player)
        {
            broadcast(new PlayerDeleted($player->user->id, $lobby->id));
            $player->lobby()->dissociate();
            $player->delete();
            broadcast(new PlayersUpdated($lobby->id, $lobby->players,'Player removed from lobby succesfully.'));
        } else {
            return response()->json(['error' => 'Player not found'], 404);
        }
    }

    public function join($lobbyId, Request $request)
    {
        $user = User::find($request->user);
        $lobby = Lobby::find($lobbyId);

        if($lobby->players()->where('user_id', $user->id)->exists())
        {
            return redirect('lobby/'.$lobby->id)->with('status', 'Już jesteś w lobby');
        }

        $player = new Player;
        $player->user()->associate($user);
        $player->lobby()->associate($lobby);
        $player->current_points = 0;
        $player->is_judge = false;

        $player->save();

        broadcast(new PlayersUpdated($lobby->id, $lobby->getCurrentPlayers(), 'Player joined lobby'));

        return redirect('lobby/'.$lobby->id);
    }

}
