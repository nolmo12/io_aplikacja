<?php

use App\Models\Set;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SetController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\LobbyController;
use App\Models\Player;

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

Route::post('lobby/update/{id}', [LobbyController::class, 'update'])
->name('lobby/update');

Route::get('/search-set', 'App\Http\Controllers\SetController@search')->name('search-set');

Route::get('sets', [SetController::class, 'index'])
->name('sets');

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

Route::delete('lobbies/{lobbyId}/sets/{setId}', [LobbyController::class, 'removeSet'])
->name('lobbies.sets.remove');

Route::delete('lobbies/{lobbyId}/player/{playerId}', [LobbyController::class, 'removePlayer'])
->name('lobbies.player.remove');

Route::delete('/sets/{setId}/cards/{cardId}', [SetController::class, 'removeCard'])->name('cards.remove');

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
