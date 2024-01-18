@extends('welcome')
@section('navbar')
<section>

    <header id="navbar">
        <div >
          <a href="main_page.php">
            <img src="{{asset('website_images/logo.png')}}" id="navbar-logo" />
          </a>
          <a href="lobby.php">
          <button class="start-game-button" >Rozpocznij grę</button>
          </a>
          <a id="nav-link1" href="decks_of_cards.php">Przeglądaj Talie</a>
    
          <a href="/profile">
            <img src="img/user-default.png" class="profile-icon" />
          </a>
    
        </div>
    </header>
    
    
    </section>
@endsection