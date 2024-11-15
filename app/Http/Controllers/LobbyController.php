<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Set;
use App\Models\Card;
use App\Models\User;
use App\Models\Lobby;
use App\Models\Player;
use App\Events\EndGame;
use App\Events\PlayCards;
use App\Events\LobbyStart;
use App\Events\SetUpdated;
use App\Models\TableCards;
use App\Events\UpdateCards;
use App\Events\UpdateJudge;
use App\Events\LobbyUpdated;
use App\Events\UpdatePoints;
use Illuminate\Http\Request;
use App\Events\AddSingleCard;
use App\Events\PlayerDeleted;
use App\Events\PlayersUpdated;
use App\Events\LobbyUpdateTime;
use App\Events\RemovePlayerCard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
        $lobby->card_id = 0;
        $lobby->save();

        $player = new Player;

        $player->user()->associate(Auth::user());
        $player->lobby()->associate($lobby);
        $player->current_points = 0;
        $player->is_judge = False;
        $player->was_judge = False;
        
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
        $random_card = $lobby->getRandomQuestionCard();
        $lobby->card_id = $random_card->id;
        $lobby->cards()->attach($random_card);
        $lobby->user_id = Auth::user()->id;
        $lobby->save();

        broadcast(new LobbyUpdated($lobby->id, $lobby->name, $lobby->round_timer, $lobby->max_rounds, $lobby->max_players));
        $players = $lobby->getCurrentPlayers();

        $judge = $players->random();

        $judge->is_judge = true;
        $judge->was_judge = true;
        $judge->save();

        $lobby->dealCards();
        $lobby->time_remaining = $lobby->round_timer;
        $lobby->current_round = 1;
        $lobby->save();

        broadcast(new LobbyStart($lobby->id));

        return redirect('lobby/'.$lobby->id)->with('status', 'Lobby: '.$lobby->name.' zostało rozpoczęte!');
    }

    public function update(Request $request)
    {
        // Assuming you have a Lobby model
        $lobby = Lobby::find($request->input('lobby_id'));

        if (!$lobby) {
            return response()->json(['error' => 'Lobby not found'], 404);
        }
        $lobby->name = $request->input('query.lobby-name');
        $lobby->round_timer = $request->input('query.turn-time') * 1000;
        $lobby->max_rounds = $request->input('query.max-rounds');
        $lobby->max_players = $request->input('query.max-players');
        $lobby->save();

        broadcast(new LobbyUpdated($lobby->id, $lobby->name, $lobby->round_timer, $lobby->max_rounds, $lobby->max_players));
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

        if($lobby->countCurrentPlayers() + 1 > $lobby->max_players)
        {
            return redirect('lobby/'.$lobby->id)->with('status', 'Lobby jest pełne');
        }

        $player = new Player;
        $player->user()->associate($user);
        $player->lobby()->associate($lobby);
        $player->current_points = 0;
        $player->is_judge = false;
        $player->was_judge = false;

        $player->save();

        broadcast(new PlayersUpdated($lobby->id, $lobby->getCurrentPlayers(), 'Player joined lobby'));

        return redirect('lobby/'.$lobby->id);
    }

    public function updateCards(Request $request)
    {
        $lobby = Lobby::find($request->input('lobby_id'));
        $card = Card::find($request->inpit('card_id'));
        $lobby->addCard($card);
    }

    public function updateTimeRemaining($lobbyId)
    {
        $lobby = Lobby::findOrFail($lobbyId);
        $lobby->time_remaining -= 1000;
        broadcast(new LobbyUpdateTime($lobby->id, $lobby->time_remaining));
    }

    public static function checkIfCardsOnTable($lobby_id)
    {
        $lobby = Lobby::find($lobby_id);
        $playersWithNoCards = [];

        $players = $lobby->getCurrentPlayers()
                    ->filter(function (Player $player){
                        return !$player->is_judge;
                    });
    
        foreach ($players as $player)
        {
            $cardsOnTable = TableCards::where('lobby_id', $lobby_id)
                ->where('player_id', $player->id)
                ->count();
    
            if ($cardsOnTable == 0) {
                $playersWithNoCards[] = $player;
            }
        }

        $playedCards = [];

        foreach ($playersWithNoCards as $playerWithoutCards) 
        {
            $card = $playerWithoutCards->playRandomCard();
        
            $tableCard = new TableCards([
                'lobby_id' => $lobby->id,
                'player_id' => $playerWithoutCards->id,
                'card_id' => $card->id,
            ]);

            $lobby->cards()->attach($card);
    
            $playerWithoutCards->remove($card);

            broadcast(new RemovePlayerCard($lobby->id, $playerWithoutCards->id, $card->id));
            
            $tableCard->save();
        
            $playedCards[] = [
                'id' => $card->id,
                'table_card_id' => $tableCard->id,
                'card_description' => $card->card_description,
            ];
        }

        broadcast(new PlayCards($lobby->id, $playedCards));
        foreach($players as $player)
        {
            $howManyCardsToAdd = 5 - $player->countCards();
            for($i = 0; $i < $howManyCardsToAdd; $i++)
            {
                $cards = $lobby->getAllAnswerCards();
                $filtered_cards = $cards->filter(function($card) use ($lobby){
                    return  !$lobby->cards()->wherePivot('card_id', $card->id)->exists();
                });

                $random_card = $filtered_cards->random();

                $player->cards()->attach($random_card);
                $lobby->addCard($random_card);

                broadcast(new UpdateCards($lobby->id, $random_card->id, $player->id));
            }

        }
    }

    public function chooseCard(Request $request)
    {
        $lobby = Lobby::find($request->input('lobby_id'));
        $player = Player::find($request->input('player_id'));
        if(!DB::table('table_cards')->where('player_id', $player->id)->first())
        {
            $card = Card::find($request->input('card_id'));
            $player->remove($card);
            $lobby->addCard($card);
            $tableCard = new TableCards([
                'lobby_id' => $lobby->id,
                'player_id' => $player->id,
                'card_id' => $card->id,
            ]);
            $tableCard->save();
            broadcast(new AddSingleCard($lobby->id, $tableCard));
            broadcast(new RemovePlayerCard($lobby->id, $player->id, $card->id));

            if(TableCards::where('lobby_id', $lobby->id)->count() === $lobby->countCurrentPlayers() - 1)
            {
                $lobby->time_remaining = 1;
                $lobby->save();
                broadcast(new PlayCards($lobby->id, $tableCard->cards));
            }

        }
    }

    public function chooseWinningCard(Request $request)
    {
        $lobby_id = $request->input('lobby_id');
        $card_id = $request->input('card_id');
        $lobby = Lobby::find($lobby_id);
        $card = Card::find($card_id);

        $table = TableCards::find($card_id);

        $player = Player::find($table->player_id);

        if($player)
        {
            $player->current_points++;
            $player->save();
            broadcast(new UpdatePoints($lobby->id, $player->id, $player->current_points));
            $random_card = $lobby->getRandomQuestionCard();
            $lobby->card_id = $random_card->id;
            $lobby->cards()->attach($random_card);
            $this->updateJudge($lobby);
            $lobby->save();
        }
    }

    public function clearTable(Request $request)
    {
        $lobby = Lobby::find($request->input('lobby_id'));

        $lobby->tableCards()->delete();
    }

    public function getJudge(Request $request)
    {
        $lobby = Lobby::find($request->input('lobby_id'));

        $currentJudge = $this->updateJudge($lobby);

        return response()->json([
            'currentJudge' => $currentJudge,
             'players' => $lobby->getCurrentPlayers(),
              'timesznyc' => $lobby->time_remaining
            ]);
    }

    public function updateJudge(Lobby $lobby)
    {
        $currentJudge = $lobby->players()->where('is_judge', true)->first();
        $player = $lobby->players()->where('was_judge', false)->first();

        if($currentJudge)
        {
            $currentJudge->is_judge = false;
            $currentJudge->save();
        }
        if ($player)
         {
            $player->is_judge = true;
            $player->was_judge = true;
            $player->save();
        }
        else 
        {
            $lobby->current_round++;

            if ($lobby->current_round <= $lobby->max_rounds)
            {
                $lobby->players()->update([
                    'is_judge' => false,
                    'was_judge' => false,
                ]);
                $lobby->save();
                $this->updateJudge($lobby);
            }
        }
        if ($lobby->current_round <= $lobby->max_rounds)
        {
            $lobby->time_remaining = $lobby->round_timer;
            $lobby->save();
        }
        else
        {
            foreach($lobby->getCurrentPlayers() as $player)
            {
                $player->cards()->detach();
                $user = $player->user;
                $user->games_played++;
                $user->save();
            }

            foreach($lobby->sets as $set)
            {
                $set->used_times++;
                $set->save();
            }

            $winner = $lobby->getBestPlayer();
            $user = $winner->user;
            $user->games_won++;
            $user->save();

            broadcast(new EndGame($lobby->id, $winner->user->name, $winner->current_points));

            $lobby->cards()->detach();
            $lobby->sets()->detach();

            $request = new Request;
            $request->merge([
                'lobby_id' => $lobby->id
            ]);

            $this->clearTable($request);
            $lobby->players()->delete();
            $lobby->delete();

        }
        broadcast(new UpdateJudge($lobby->id));

        return $currentJudge;
    }

    public function showWinner(Request $request)
    {
        return view("lobbies.end",[
            'user_name' => $request->input('user_name'),
            'points' => $request->input('points')
        ]);
    }

}
