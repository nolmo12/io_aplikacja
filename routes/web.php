<?php

use App\Models\Set;
use App\Models\Card;
use App\Models\User;
use App\Models\Player;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SetController;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\CardController;
use App\Http\Controllers\LobbyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');

Route::get('user/{id}', function($id){
    return User::find($id);
});

Route::get('player/{id}', function($id){
    return Player::find($id);
});

Route::get('card/{id}', function($id){
    return Card::find($id);
});

Route::get('card/{player_id}/{card_id}', function($player_id, $card_id){
    $card = Card::whereHas('players', function ($query) use ($player_id) {
        $query->where('player_id', $player_id);
    })->find($card_id);

    return $card;
});

Route::get('lobbies', [LobbyController::class, 'index'])
->name('lobbies');

Route::get('lobbies/create/{id}', [LobbyController::class, 'create'])
->name('lobbies/create');

Route::get('lobbies/create_temp', [LobbyController::class, 'create_temp'])
->name('lobbies/create_temp');

Route::post('lobbies/store/{id}', [LobbyController::class, 'store'])
->name('lobbies/store');

Route::post('lobbies/join/{id}', [LobbyController::class, 'join'])
->name('lobbies/create');

Route::get('lobby/{id}', [LobbyController::class, 'create'])
->name('lobby');

Route::get('lobby/data/update/', 'App\Http\Controllers\LobbyController@update')
->name('lobby/data/update/');

Route::get('/search-set', 'App\Http\Controllers\SetController@search')
->name('search-set');

Route::get('lobby/cards/update/', 'App\Http\Controllers\LobbyController@updateCards')
->name('update-cards');

Route::get('/update-time-remaining/{lobbyId}', 'App\Http\Controllers\LobbyController@updateTimeRemaining')
->name('update-time-remaining');

Route::get('/check-if-cards-on-table/{lobbyId}', 'App\Http\Controllers\LobbyController@checkIfCardsOnTable')
->name('check-if-cards-on-table');

Route::delete('lobbies/{lobbyId}/sets/{setId}', [LobbyController::class, 'removeSet'])
->name('lobbies.sets.remove');

Route::delete('lobbies/{lobbyId}/player/{playerId}', [LobbyController::class, 'removePlayer'])
->name('lobbies.player.remove');

Route::get('/choose-winning-card', 'App\Http\Controllers\LobbyController@chooseWinningCard')
    ->name('choose-winning-card');

Route::get('/clear-table', 'App\Http\Controllers\LobbyController@clearTable')
    ->name('clear-table');

Route::get('/get-judge', 'App\Http\Controllers\LobbyController@getJudge')
    ->name('get-judge');

Route::get('/choose-card', 'App\Http\Controllers\LobbyController@chooseCard')
    ->name('choose-card');

Route::get('show-winner', 'App\Http\Controllers\LobbyController@showWinner')
    ->name('show-winner');

Route::get('sets', [SetController::class, 'index'])
->name('sets');

Route::get('sets/popular', [SetController::class, 'popular'])
->name('sets/popular');

Route::get('sets/recent', [SetController::class, 'recent'])
->name('sets/recent');

Route::get('sets/all', [SetController::class, 'all'])
->name('sets/all');

Route::get('set/{id}', [SetController::class, 'show'])
->name('set');

Route::get('sets/user={id}', [SetController::class, 'browseOwnSets'])
->name('sets/user=');


Route::get('sets/edit/{id}', [SetController::class, 'edit'])
->name('sets/edit');

Route::post('sets/update/{id}', [SetController::class, 'update'])
->name('sets/update');

Route::get('sets/create', [SetController::class, 'create'])
->name('sets/create');

Route::post('sets/store', [SetController::class, 'store'])
->name('sets/store');

Route::DELETE('/remove/cards/{cardId}', [SetController::class, 'removeCard'])->name('cards.remove');

Route::view('dashboard', 'dashboard', ['sets' => Set::getPopularSets(3)])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::get('profile/{id}', function($id){
    return view("profile_other",[
       'user' => User::find($id) 
    ]);
})
->name('profile/');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
