<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Strona główna</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="{{ URL::asset('css/main-page.css') }}" rel="stylesheet"/>
        @if(request()->routeIs('lobbies'))
        <link href="{{ URL::asset('css/lobby.css') }}" rel="stylesheet"/>
        @endif
        @if(request()->routeIs('lobby'))
        <link href="{{ URL::asset('css/create-lobby.css') }}" rel="stylesheet"/>
        @endif
        @if(request()->routeIs('sets'))
        <link href="{{ URL::asset('css/deck-of-cards.css') }}" rel="stylesheet"/>
        @endif
        @if(request()->routeIs('profile/'))
        <link href="{{ URL::asset('css/user_profile.css') }}" rel="stylesheet"/>
        @endif
        @if(request()->routeIs('sets/edit'))
        <link href="{{ URL::asset('css/create_deck.css') }}" rel="stylesheet"/>
        @endif


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script src="{{ URL::asset('js/jquery.js') }}"></script>
        <script src="{{ URL::asset('js/app.js') }}"></script>
    </head>
    <body class="antialiased">
        <section>

            <header class="main-header">
                <div class="navbar-container">
                    <div class="logo-container">
                        <a href="{{route('dashboard')}}" class="logo-link">
                            <img src="{{asset("website_images/logo.png")}}" class="navbar-logo" alt="Logo" />
                        </a>
                    </div>
            
                    <div class="center-container">
                        <a href="{{route('lobbies')}}" class="nav-link">
                            <button class="start-game-button">Rozpocznij grę</button>
                        </a>
                    </div>
            
                    <div class="right-container">
                        <a href="{{route('sets')}}" class="options">Przeglądaj Talie</a>
                        <a href="{{route('profile')}}" class="nav-link">
                            @if(Auth::user()->profile_picture)
                            <img class="profile-icon" alt="Profil"  src="{{asset('storage/images/'.Auth::user()->profile_picture)}}">
                           @endif
                        </a>
                        <a href="{{route('profile')}}" class="options">{{auth()->user()->name}}</a>
                    </div>
                </div>
            </header>
            
            
            </section>
        @yield('content')
    </body>
</html>
