<?php

namespace App\Http\Controllers;

use App\Models\Lobby;
use Illuminate\Http\Request;

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
}
