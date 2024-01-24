<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WinnerController extends Controller
{
    public function show()
    {


        return view('lobbies.end', compact('winner'));
    }
}
