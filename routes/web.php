<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\LobbyController;
use App\Http\Controllers\SetController;
use Illuminate\Support\Facades\Route;

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

Route::get('lobbies', [LobbyController::class, 'index'])
->name('lobbies');

Route::get('lobbies/create', [LobbyController::class, 'create'])
->name('lobbies/create');

Route::post('lobbies/store', [LobbyController::class, 'store'])
->name('lobbies/store');

Route::get('lobby/{id}', [LobbyController::class, 'show'])
->name('lobby');


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

Route::delete('/sets/{setId}/cards/{cardId}', [SetController::class, 'removeCard'])->name('cards.remove');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
