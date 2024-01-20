@extends('layout')
@section('content')
@if(auth()->user()->id === $lobby->owner->id && $lobby->card_id === 0)
<div style="background-color: #20222a">
<div class="empty-div"></div>
<form name="add-lobby-post-form" id="add-lobby-post-form" method="post" action="{{url('lobbies/store/'.$lobby->id)}}">
  @csrf 
<div id="game-room">
  <div id="lobby-title"><input id="lobby-title" value="{{$lobby->name}}" name="lobby_name" style="border: none; background: none; outline: none;"></div>
  <div id="players-list">
    <ul>
      <h2>Lista graczy:</h2>
      @foreach($lobby->getCurrentPlayers() as $player)
        @if($player->user->name === auth()->user()->name)
        <a href="{{route('profile/', ['id' => $player->user->id])}}"><li>{{$player->user->name}}</li></a>
        @else
        <a href="{{route('profile/', ['id' => $player->user->id])}}"><li>{{$player->user->name}}</a><button type="button" onclick="removePlayer({{$lobby->id}}, {{$player->id}})">Usuń</button></li>
        @endif
      @endforeach
      </ul>
  </div>
  
  <div id="game-content">
    @if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
  @endif  
      <div id="game-settings"> 
          <label for="turn-time">Czas tury:</label>
          <input type="number" id="turn-time" min="1" max="60" name ="turn_timer" value="{{$lobby->round_timer / 1000}}">
          
          <label for="rounds-number">Liczba rund:</label>
          <input type="number" name = "max_rounds" min="1" max="10" value="{{$lobby->max_rounds}}">
          <label for="players-number">Maksymalna Liczba Graczy:</label>
          <input type="number" name = "max_players" min="1" max="20" value="{{$lobby->max_players}}">
      </div>

      <div id='deck-section'>
          <div id='deck-section-add'>
            <p class="add-deck-text">Dodaj Talie:</p>
              <button type="button" onclick="toggleInput()" class="back-button">+</button>
              <div id="inputContainer" class="hidden">
                  <input type="text" name="set_code" id="textInput" placeholder="Wpisz tekst">
              </div>
          </div>
          <div id="deck-section-decks">
              <!-- decki które dodał gracz -->
              
                  <section id = "sets">
                  @foreach($lobby->sets as $set)
                  <div class="card-container" id="card-sznycer{{$set->id}}">
                    <div class="card"> 
                      <p class="card-text">{{$set->name}}, KOD: {{$set->reference_code}}</p>
                      <button type="button" onclick="removeSet({{$lobby->id}}, {{$set->id}})" class="remove-button" >Usuń talię</button>
                    </div>
                      <div class="card"></div>    
                  </div>
                  @endforeach
                  </section>
              
          </div>
      </div>

      <div id="game-actions">
          Link do rozgrywki: 
          <a href="{{ route('lobby', ['id' => $lobby->id]) }}" id='game-link'>{{ route('lobby', ['id' => $lobby->id]) }}</a>

          <a href="{{route('lobbies')}}"><button type="button" class="back-button">< Powrót</button></a>
          <a href=""></a><button type="submit" class="start-button">Rozpocznij</button></a>
      
      </div>
  </div>
</div> 
</div>  
</form>

@elseif(auth()->user()->id != $lobby->owner->id && $lobby->card_id === 0)
<div style="background-color: #20222a">
<div class="empty-div"></div>
<form name="add-lobby-post-form" id="add-lobby-post-form" method="post" action="{{url('lobbies/join/'.$lobby->id)}}">
  @csrf 
  <input type="hidden" name="user" value="{{auth()->user()->id}}">
<div id="game-room">
  <div id="lobby-title"><input id="lobby-title" readonly value="{{$lobby->name}}" style="border: none; background: none; outline: none;"></div>
  <div id="players-list">
    <ul>
      <h2>Lista graczy:</h2>
      @foreach($lobby->getCurrentPlayers() as $player)
        <a href="{{route('profile/', ['id' => $player->user->id])}}"><li>{{$player->user->name}}</li></a>
      @endforeach
      </ul>
  </div>
  
  <div id="game-content">
    @if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
  @endif  
      <div id="game-settings"> 
          <label for="turn-time">Czas tury:</label>
          <input type="number" id="turn-time" min="1" max="60" value="{{$lobby->round_timer / 1000}}" readonly>
          
          <label for="rounds-number">Liczba rund:</label>
          <input type="number" min="1" max="10" value="{{$lobby->max_rounds}}" readonly>
          <label for="players-number">Maksymalna Liczba Graczy:</label>
          <input type="number" min="1" max="10" value="{{$lobby->max_players}}" readonly>
      </div>

      <div id='deck-section'>
          <div id="deck-section-decks">
              <!-- decki które dodał gracz -->
              
                  <section id = "sets">
                  @foreach($lobby->sets as $set)
                  <div class="card-container" id="card-sznycer{{$set->id}}">
                    <div class="card"> 
                      <p class="card-text">{{$set->name}}, KOD: {{$set->reference_code}}</p>
                    </div>
                      <div class="card"></div>    
                  </div>
                  @endforeach
                  </section>
              
          </div>
      </div>

      <div id="game-actions">
          Link do rozgrywki: 
          <a href="{{ route('lobby', ['id' => $lobby->id]) }}" id='game-link'>{{ route('lobby', ['id' => $lobby->id]) }}</a>

          <a href="{{route('lobbies')}}"><button type="button" class="back-button">< Powrót</button></a>
          @if($lobby->players()->where('user_id', auth()->user()->id)->first())
            @if(auth()->user()->id != $lobby->players()->where('user_id', auth()->user()->id)->first()->user_id)
              <a href=""></a><button type="submit" class="start-button">Dołącz</button></a>
            @else
              <a href=""></a><button type="button" onclick="removePlayer({{$lobby->id}}, {{App\Models\Player::where('user_id', auth()->user()->id)->first()->id}})" class="start-button">Opuść</button></a>
            @endif
          @else
          <a href=""></a><button type="submit" class="start-button">Dołącz</button></a>
          @endif
      
      </div>
  </div>
</div> 
</div>  
</form>
@else
sznyc2    
@endif
<script>
  function toggleInput() {
      var inputContainer = document.getElementById("inputContainer");

      // Jeśli div jest ukryty, pokaż go; jeśli jest widoczny, ukryj go
      inputContainer.classList.toggle("hidden");
  }
  function removeSet(lobbyId, setId)
  {
            var xhr = new XMLHttpRequest();
            xhr.open('DELETE', '/lobbies/' + lobbyId + '/sets/' + setId, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200)
                     {
                      var setElement = document.getElementById("card-sznycer" + setId);
                        setElement.remove();
                    }
                    else 
                    {
                        // Handle the error case
                        console.error('Error removing set:', xhr.responseText);
                    }
                }
            };
            xhr.send();
  }

  function removePlayer(lobbyId, playerId)
  {
            var xhr = new XMLHttpRequest();
            xhr.open('DELETE', '/lobbies/' + lobbyId + '/player/' + playerId, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200)
                     {
                    }
                    else 
                    {
                        // Handle the error case
                        console.error('Error removing player:', xhr.responseText);
                    }
                }
            };
            xhr.send();
  }

  function updateSetsContainer(sets) 
  {
    // Clear the current content of the sets container
    $('#sets').empty();

    // Iterate through the updated sets and append them to the container
    sets.forEach(function(set) {
        if({{auth()->user()->id}} === {{$lobby->owner->id}})
        {
          var cardContainer = $('<div class="card-container" id="card-sznycer'+ set.id + '"></div>');
        var card = $('<div class="card"><p class="card-text">' + set.name + ', KOD: ' + set.reference_code + '</p> <button type="button" onclick="removeSet(' + {{$lobby->id}} + ', ' + set.id + ')" class="remove-button">Usuń deck</button></div><div class="card"></div>');
        cardContainer.append(card);
        }
        else
        {
          var cardContainer = $('<div class="card-container" id="card-sznycer'+ set.id + '"></div>');
        var card = $('<div class="card"><p class="card-text">' + set.name + ', KOD: ' + set.reference_code + '</p></div><div class="card"></div>');
        cardContainer.append(card);
        }
        $('#sets').append(cardContainer);
    });
  }

  function updatePlayers(players) {
    $('#players-list').empty();
    $('#players-list').append('Lista graczy:')

      var list = $('<ul></ul>');

      players.forEach(function (player) {
          // Make an AJAX request to fetch user data based on user_id
          $.ajax({
              url: '/user/' + player.user_id,
              type: 'GET',
              success: function (userData) {
                if({{auth()->user()->id}} === {{$lobby->owner->id}} && {{$lobby->owner->id}} != userData.id)
                {
                  var listElement = $('<li><a href="">' + userData.name + '</a><button type="button" onclick="removePlayer(' + {{$lobby->id}} + ', ' + player.id + ')"> Usuń </button></li>');
                  list.append(listElement);
                }
                else
                {
                  var listElement = $('<li><a href="">' + userData.name + '</a></li>');
                  list.append(listElement);
                }
                  $('#players-list').append(list);
              },
              error: function (error) {
                  console.error('Error fetching user data:', error);
              }
          });
      });
}

  $('#turn-time').on('input', function() {
    var xhr = new XMLHttpRequest();
    var formData = new FormData();  // Create FormData object

    formData.append('turn_time', $('#turn-time').val());  // Add data to FormData

    xhr.open('POST', '/lobby/update/' + {{$lobby->id}}, true);
    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          // Parse the response JSON to get the updated timer value
          var response = JSON.parse(xhr.responseText);

          // Update the input value with the updated timer value
          $('#turn-time').val(response.updated_timer / 1000);
        } else {
          // Handle the error case
          console.error('Error:', xhr.responseText);
        }
      }
    };

    // Send the request with FormData
    xhr.send(formData);
  });

  $('#textInput').on('input', function() {
        var lobby = {{$lobby->id}};
        var query = $(this).val();

        // Make an AJAX request to the server
        $.ajax({
            type: 'GET',
            url: "{{ route('search-set') }}", // Wrap the route function in quotes
            data: { query: query, lobby_id : lobby },
            success: function(data) {
                console.log(data);
                // Handle the data returned from the server
                //$('#searchResults').html(data);
            }
        });
    });

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('eae19956aee9c0efda16', {
      cluster: 'eu'
    });
    var channel = pusher.subscribe('lobby.' + {{$lobby->id}});

// Bind a callback function to handle lobby events
channel.bind('App\\Events\\SetUpdated', function(data) {
  updateSetsContainer(data.lobby_sets);
});

channel.bind('App\\Events\\PlayersUpdated', function(data) {
    updatePlayers(data.players);
});

channel.bind('App\\Events\\PlayerDeleted', function(data) {
  if(data.user_id == {{auth()->user()->id}})
  {
    alert("Zostałeś usunięty z lobby");
    location.reload();
  }
});



</script> 
@endsection